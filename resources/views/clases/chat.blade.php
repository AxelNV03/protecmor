@hasanyrole('profesor|alumno')
<div 
  x-data="chatClaseRealtime({ 
      fetchUrl: '{{ route('clases.chat.index', $clase) }}', 
      postUrl:  '{{ route('clases.chat.store', $clase) }}', 
      channel:  'clase.{{ $clase->id }}',
      me: {{ auth()->id() }} 
  })" 
  x-show="activeTab === 'chat'"
  x-init="init()"
  class="flex flex-col h-[60vh] border rounded-md"
>
  <!-- Historial -->
  <div id="chat-scroll" class="flex-1 overflow-y-auto p-4 space-y-3 bg-gray-50">
    <template x-for="m in messages" :key="m.id">
      <div class="max-w-[80%]" :class="m.user_id === me ? 'ml-auto text-right' : 'mr-auto text-left'">
        <div class="text-xs text-gray-500" x-text="m.user_id === me ? 'Tú' : m.user_name"></div>
        <div class="inline-block px-3 py-2 rounded-lg"
             :class="m.user_id === me ? 'bg-blue-600 text-white' : 'bg-white border'">
          <div x-text="m.contenido"></div>
          <div class="mt-1 text-[10px] opacity-70" x-text="m.fecha"></div>
        </div>
      </div>
    </template>
    <template x-if="messages.length === 0">
      <div class="text-center text-gray-400 text-sm">No hay mensajes aún. ¡Escribe el primero!</div>
    </template>
  </div>

  <!-- Enviar -->
  <form @submit.prevent="send" class="border-t p-3 flex gap-2 bg-white">
    @csrf
    <input x-model="newMessage" type="text" class="flex-1 border rounded px-3 py-2" placeholder="Escribe tu mensaje...">
    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Enviar</button>
  </form>
</div>
@endhasanyrole

<script>
function chatClaseRealtime({ fetchUrl, postUrl, channel, me }) {
  return {
    messages: [],
    newMessage: '',
    init() {
      // 1) Cargar historial
      this.load(true);
      // 2) Suscribirse al canal privado
      if (window.Echo) {
        window.Echo.private(channel)
          .listen('.message.sent', (e) => {
            this.messages.push(e);
            this.$nextTick(() => {
              const cont = document.getElementById('chat-scroll');
              cont.scrollTop = cont.scrollHeight;
            });
          });
      }
    },
    async load(scrollToBottom = false) {
      try {
        const res = await fetch(fetchUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' }});
        if (!res.ok) return;
        const data = await res.json();
        this.messages = data;
        this.$nextTick(() => {
          const cont = document.getElementById('chat-scroll');
          if (scrollToBottom) cont.scrollTop = cont.scrollHeight;
        });
      } catch (e) {}
    },
    async send() {
      const text = this.newMessage?.trim();
      if (!text) return;
      this.newMessage = '';
      try {
        const res = await fetch(postUrl, {
          method: 'POST',
          headers: { 
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest'
          },
          body: JSON.stringify({ contenido: text })
        });
        // No hace falta empujar el mensaje aquí porque el evento lo envía a todos (incluyéndote)
      } catch (e) {}
    },
    me
  }
}
</script>
