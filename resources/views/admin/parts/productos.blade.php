@php
    // Usaremos esta ruta para cargar los datos de forma asíncrona (AJAX)
    $productos_data_route = route('admin.productos.data'); 
    $store_route = route('admin.productos.store'); // Ruta para crear nuevos productos
@endphp

<div 
    x-data="productosModule()"
    x-show="activeTab === 'productos'" {{-- Asumiendo que 'productos' es el nombre de tu pestaña --}}
    x-init="fetchProductos('{{ $productos_data_route }}')" {{-- Carga inicial de datos --}}
    class="p-4"
>

    <h2 class="text-2xl font-bold mb-4">Gestión de Productos 📦</h2>

    <div class="flex flex-wrap justify-between items-center mb-4">
        <input type="text" placeholder="Buscar por nombre o categoría..." 
               x-model.debounce.500ms="search" 
               @input="fetchProductos('{{ $productos_data_route }}')" 
               class="border p-2 w-full sm:w-1/2 md:w-1/3 rounded shadow-sm focus:ring-blue-500 focus:border-blue-500">

        <button @click="openAddModal()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-4 py-2 rounded transition duration-150 ease-in-out mt-2 sm:mt-0 shadow-md">
            ➕ Agregar Producto
        </button>
    </div>
    
    <div x-show="message" x-text="message" 
         x-transition:enter.duration.500ms 
         x-init="$watch('message', value => { if (value) setTimeout(() => message = '', 3000) })"
         class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 shadow-inner" role="alert">
    </div>

    <div x-show="loading" class="text-center py-10 text-xl text-gray-500">
        Cargando productos...
    </div>

    <div x-show="!loading" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
        <template x-for="p in productos.data" :key="p.id">
            <div class="border p-4 rounded shadow-lg hover:shadow-xl transition duration-300 ease-in-out cursor-pointer" @click="openDetailModal(p)">
                <img :src="p.imagen ? `/storage/${p.imagen}` : '/img/default.png'" class="w-full h-40 object-cover rounded mb-3 border border-gray-200">
                <h3 class="font-bold text-lg truncate" x-text="p.nombre"></h3>
                <p class="text-xl text-green-600 font-semibold" x-text="'$' + parseFloat(p.precio).toFixed(2)"></p>
                <span :class="p.disponible ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800'" 
                      class="text-xs font-semibold px-2 py-0.5 rounded-full" 
                      x-text="p.disponible ? 'Disponible' : 'No Disponible'">
                </span>
            </div>
        </template>
        <template x-if="productos.data && productos.data.length === 0 && !loading">
            <p class="col-span-full text-center text-gray-500 py-10">No se encontraron productos.</p>
        </template>
    </div>

    <div x-html="productos.links_html" class="mt-6 flex justify-center"></div>

    
    <div x-show="showDetailModal" 
         @keydown.escape.window="showDetailModal = false"
         x-transition.opacity
         class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60 p-4">
        <div @click.outside="showDetailModal = false"
             class="bg-white p-6 rounded-lg shadow-2xl w-full max-w-md">
            
            <h3 class="text-2xl font-bold mb-4 border-b pb-2" x-text="selectedProducto.nombre"></h3>
            <img :src="selectedProducto.imagen ? `/storage/${selectedProducto.imagen}` : '/img/default.png'" 
                 class="w-full h-48 object-cover rounded mb-4 border shadow-md">
            
            <div class="space-y-2 text-gray-700">
                <p><strong>Precio de Venta:</strong> <span class="font-semibold text-green-600" x-text="'$' + parseFloat(selectedProducto.precio).toFixed(2)"></span></p>
                <p><strong>Costo de Compra:</strong> <span x-text="selectedProducto.costo ? '$' + parseFloat(selectedProducto.costo).toFixed(2) : 'N/A'"></span></p>
                <p><strong>Categoría:</strong> <span x-text="selectedProducto.categoria"></span></p>
                <p><strong>Disponibilidad:</strong> 
                    <span :class="selectedProducto.disponible ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'" 
                          class="font-semibold px-2 py-0.5 rounded-full text-sm" 
                          x-text="selectedProducto.disponible ? 'Disponible' : 'No Disponible'">
                    </span>
                </p>
                <p class="mt-4"><strong>Descripción:</strong> <span x-text="selectedProducto.descripcion || 'Sin descripción'"></span></p>
            </div>

            <div class="mt-6 pt-4 border-t flex justify-between">
                <button @click="openEditModal(selectedProducto)" 
                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded transition duration-150 ease-in-out shadow-md">
                    ✏️ Editar
                </button>
                <button @click="deleteProducto(selectedProducto.id)" 
                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded transition duration-150 ease-in-out shadow-md">
                    🗑️ Eliminar
                </button>
                <button @click="showDetailModal = false" 
                        class="bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded transition duration-150 ease-in-out shadow-md">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
    
    <div x-show="showFormModal" 
         @keydown.escape.window="showFormModal = false"
         x-transition.opacity
         class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60 p-4">
        <div @click.outside="showFormModal = false"
             class="bg-white p-6 rounded-lg shadow-2xl w-full max-w-lg overflow-y-auto max-h-[90vh]">
            
            <h3 class="text-2xl font-bold mb-4 border-b pb-2" x-text="editMode ? 'Editar Producto' : 'Agregar Producto'"></h3>

            <form @submit.prevent="saveProducto" enctype="multipart/form-data">
                
                <input type="hidden" name="id" x-model="form.id">

                <div class="mb-3">
                    <label class="block text-gray-700 text-sm font-bold mb-1">Nombre</label>
                    <input type="text" x-model="form.nombre" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <p class="text-red-500 text-xs italic" x-text="errors.nombre ? errors.nombre[0] : ''"></p>
                </div>

                <div class="mb-3">
                    <label class="block text-gray-700 text-sm font-bold mb-1">Descripción</label>
                    <textarea x-model="form.descripcion" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    <p class="text-red-500 text-xs italic" x-text="errors.descripcion ? errors.descripcion[0] : ''"></p>
                </div>

                <div class="flex space-x-4 mb-3">
                    <div class="w-1/2">
                        <label class="block text-gray-700 text-sm font-bold mb-1">Precio de Venta</label>
                        <input type="number" x-model="form.precio" step="0.01" min="0" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <p class="text-red-500 text-xs italic" x-text="errors.precio ? errors.precio[0] : ''"></p>
                    </div>

                    <div class="w-1/2">
                        <label class="block text-gray-700 text-sm font-bold mb-1">Costo de Compra</label>
                        <input type="number" x-model="form.costo" step="0.01" min="0" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <p class="text-red-500 text-xs italic" x-text="errors.costo ? errors.costo[0] : ''"></p>
                    </div>
                </div>

                <div class="flex space-x-4 mb-4">
                    <div class="w-1/2">
                        <label class="block text-gray-700 text-sm font-bold mb-1">Categoría</label>
                        <input type="text" x-model="form.categoria" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <p class="text-red-500 text-xs italic" x-text="errors.categoria ? errors.categoria[0] : ''"></p>
                    </div>

                    <div class="w-1/2">
                        <label class="block text-gray-700 text-sm font-bold mb-1">Disponibilidad</label>
                        <select x-model="form.disponibilidad" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option :value="1">Disponible</option>
                            <option :value="0">No Disponible</option>
                        </select>
                        <p class="text-red-500 text-xs italic" x-text="errors.disponible ? errors.disponible[0] : ''"></p>
                    </div>
                </div>

                <div class="mb-4 border-t pt-4">
                    <label class="block text-gray-700 text-sm font-bold mb-1">Imagen (Máx. 2MB)</label>
                    <input type="file" @change="form.imagen_file = $event.target.files[0]" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-red-500 text-xs italic" x-text="errors.imagen ? errors.imagen[0] : ''"></p>
                    <template x-if="editMode && form.imagen">
                        <p class="mt-2 text-sm text-gray-500">Imagen actual: <a :href="`/storage/${form.imagen}`" target="_blank" class="text-blue-500 hover:underline">Ver Imagen</a></p>
                    </template>
                </div>

                <div class="flex justify-between mt-6 pt-3 border-t">
                    <button type="submit" :disabled="loading" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150 ease-in-out shadow-md disabled:opacity-50">
                        <span x-text="editMode ? 'Actualizar Producto' : 'Guardar Producto'"></span>
                    </button>
                    <button type="button" @click="closeFormModal()" class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150 ease-in-out shadow-md">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function productosModule() {
        return {
            search: '',
            loading: true, 
            message: '',
            showDetailModal: false,
            showFormModal: false,
            editMode: false,
            selectedProducto: {},
            // Objeto de paginación inicial
            productos: { data: [], links_html: '', current_page_url: '{{ $productos_data_route }}' }, 
            errors: {},
            
            form: {
                id: null,
                nombre: '',
                precio: 0.00, 
                categoria: '',
                disponible: 1, 
                costo: null, 
                descripcion: '',
                imagen_file: null, 
            },

            generatePaginationLinks() {
                let links = '';
                if (!this.productos.links) return;

                this.productos.links.forEach(link => {
                    const activeClass = link.active ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300';
                    const disabledClass = link.url ? '' : 'opacity-50 cursor-not-allowed';
                    
                    if (link.url) {
                         links += `<button @click.prevent="fetchProductos('${link.url}')" class="px-3 py-1 mx-1 rounded text-sm ${activeClass} ${disabledClass}" ${link.url ? '' : 'disabled'}>${link.label}</button>`;
                    } else {
                         links += `<span class="px-3 py-1 mx-1 rounded text-sm ${activeClass} ${disabledClass}">${link.label}</span>`;
                    }
                });
                this.productos.links_html = links;
            },
            
            async fetchProductos(url) {
                this.loading = true;
                this.errors = {};
                
                const fullUrl = new URL(url);
                if (this.search) {
                    fullUrl.searchParams.set('search', this.search);
                }

                try {
                    const response = await axios.get(fullUrl.toString());
                    
                    this.productos = response.data.productos; 
                    this.productos.current_page_url = fullUrl.toString();
                    this.generatePaginationLinks();
                } catch (e) {
                    console.error('Error al cargar productos:', e);
                    this.message = 'Error al cargar productos.';
                } finally {
                    this.loading = false;
                }
            },
            
            // --- Funciones de Modales ---
            
            openDetailModal(producto) {
                this.selectedProducto = producto;
                this.showDetailModal = true;
            },

            closeFormModal() {
                this.showFormModal = false;
                this.errors = {}; 
            },
            
            openAddModal() {
                this.editMode = false;
                this.form = {
                    id: null, nombre: '', precio: 0.00, categoria: '', 
                    disponibilidad: 1, costo: null, descripcion: '', 
                    imagen_file: null,
                };
                this.showFormModal = true;
            },

            openEditModal(producto) {
                this.editMode = true;
                this.form = {
                    id: producto.id,
                    nombre: producto.nombre,
                    precio: parseFloat(producto.precio), // <--- CORREGIDO
                    categoria: producto.categoria,
                    disponible: producto.disponible ? 1 : 0, // <--- CORREGIDO
                    costo: producto.costo ? parseFloat(producto.costo) : null, // <--- CORREGIDO
                    descripcion: producto.descripcion,
                    imagen: producto.imagen, 
                    imagen_file: null, 
                };
                this.showDetailModal = false;
                this.showFormModal = true;
                this.errors = {};
            },
            
            // --- Funciones CRUD ---

            async saveProducto() {
                this.loading = true;
                this.errors = {};
                
                const formData = new FormData();
                
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

                // Adjuntar campos de texto
                for (const key in this.form) {
                    if (key !== 'imagen' && key !== 'imagen_file' && this.form[key] !== null) {
                        formData.append(key, this.form[key]);
                    }
                }

                // Adjuntar el archivo de imagen
                if (this.form.imagen_file) {
                    formData.append('imagen', this.form.imagen_file);
                }
                
                let url;
                let method;
                
                if (this.editMode) {
                    url = `/admin/productos/${this.form.id}`;
                    method = 'POST'; 
                    formData.append('_method', 'PUT');
                } else {
                    url = storeRoute;
                    method = 'POST';
                }

                try {
                    const response = await axios({
                        method: method,
                        url: url,
                        data: formData,
                    });
                    
                    this.message = response.data.message;
                    this.showFormModal = false;
                    this.fetchProductos(this.productos.current_page_url); 

                } catch (error) {
                    if (error.response) {
                        if (error.response.status === 422) {
                            // Error de validación de Laravel
                            this.errors = error.response.data.errors;
                            this.message = 'Corrija los errores del formulario.';
                        } else if (error.response.status === 419) {
                            // Error de CSRF
                            this.message = 'Error de seguridad. Por favor, recargue la página.';
                        } else {
                            // Error 500 o similar
                            console.error('Error del servidor:', error.response.data);
                            this.message = 'Error desconocido al guardar el producto. Revise la consola.';
                        }
                    } else {
                        console.error('Error de red:', error);
                        this.message = 'Error de red al intentar guardar el producto.';
                    }
                } finally {
                    this.loading = false;
                }
            },

            async deleteProducto(id) {
                if (!confirm('¿Está seguro de eliminar este producto? Esta acción es irreversible.')) {
                    return;
                }
                
                this.loading = true;
                this.showDetailModal = false;

                try {
                    const url = `/admin/productos/${id}`;
                    const response = await axios.delete(url);

                    this.message = response.data.message;
                    // Refrescar lista manteniendo la página actual
                    this.fetchProductos(this.productos.current_page_url); 

                } catch (error) {
                    console.error('Error al eliminar producto:', error);
                    this.message = 'Error al eliminar el producto.';
                } finally {
                    this.loading = false;
                }
            }
        }
    }
</script>