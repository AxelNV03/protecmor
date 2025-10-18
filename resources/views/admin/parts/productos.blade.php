{{-- resources/views/admin/parts/productos.blade.php --}}
@php
    // Usamos route() para TODAS las URLs, ahora que la definición de rutas es limpia.
    // Si sigue fallando, es OBLIGATORIO ejecutar php artisan route:clear y cache:clear
    $productosDataRoute = route('admin.productos.data');
    $productosStoreRoute = route('admin.productos.store');
    $productosUpdatePrefix = route('admin.productos.update', '');
    $productosDestroyPrefix = route('admin.productos.destroy', '');
    
    $categoriasJson = json_encode(\App\Models\Producto::getCategorias());

    // Preparar datos antiguos para errores de validación
    $oldInput = json_encode(session()->getOldInput() ?: []);
    $oldInput = addslashes($oldInput);
@endphp

{{-- Componente Alpine.js --}}
<div 
    x-data="{ 
        productos: [], 
        categorias: JSON.parse('{{ $categoriasJson }}'),
        isLoading: true,
        showModal: {{ $errors->any() ? 'true' : 'false' }},
        isEditing: {{ old('_method') == 'PUT' ? 'true' : 'false' }},
        currentProduct: @if($errors->any()) JSON.parse('{{ $oldInput }}') @else {} @endif,
        
        showConfirmation: false, 
        
        fetchData() {
            this.isLoading = true;
            fetch('{{ $productosDataRoute }}')
                .then(response => response.json())
                .then(data => {
                    this.productos = data;
                    this.isLoading = false;
                })
                .catch(error => {
                    console.error('Error al cargar los productos:', error);
                    this.isLoading = false;
                });
        },
        
        editProduct(product) {
            this.isEditing = true; 
            this.currentProduct = {
                ...product, 
                disponible: product.disponible ? 1 : 0, 
                visible: product.visible ? 1 : 0
            }; 
            this.showModal = true;
        },

        newProduct() {
            this.isEditing = false;
            this.currentProduct = {};
            document.getElementById('productForm').reset(); 
            this.showModal = true;
        }
    }" 
    x-show="activeTab === 'productos'"
    x-init="
        fetchData();
        
        @if(session('success'))
            setTimeout(() => { 
                alert('{{ addslashes(session('success')) }}'); 
            }, 100);
        @endif

        $watch('activeTab', (value) => {
            if (value === 'productos') {
                fetchData();
            }
        });
    "
>
    <h2 class="mb-4">Gestión de Productos 📦</h2>

    <!-- Bloque de errores de validación de Laravel -->
    @if ($errors->any())
        <div class="alert alert-danger mb-4" style="background-color: #f8d7da; color: #721c24; padding: 1rem; border-radius: 0.25rem;">
            <strong>¡Error de validación!</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <!-- Botón Agregar Producto -->
    <button @click="newProduct()" class="px-3 py-2 bg-blue-600 text-white rounded shadow mb-4">
        <i class="fas fa-plus"></i> Agregar Producto
    </button>

    <!-- Indicador de Carga -->
    <div x-show="isLoading" class="text-center py-4">
        Cargando productos...
    </div>

    <!-- Lista de Productos (Vista tipo tarjeta) -->
    <div x-show="!isLoading && productos.length > 0" class="row">
        <template x-for="producto in productos" :key="producto.id">
            <div class="col-md-4 mb-4">
                <div class="p-3 border rounded shadow-sm hover:shadow-md transition duration-300">
                    <img :src="producto.imagen || '{{ asset('images/default-product.png') }}'" 
                         alt="Imagen de producto" 
                         class="w-full h-40 object-cover rounded mb-2"
                         style="height: 150px; object-fit: cover;">
                    
                    <h5 class="font-bold text-lg" x-text="producto.nombre"></h5>
                    <p class="text-sm text-gray-600">Categoría: <span x-text="producto.categoria"></span></p>
                    <p class="text-xl font-semibold text-green-700">$ <span x-text="parseFloat(producto.precio).toFixed(2)"></span></p>
                    
                    <span :class="{ 
                        'bg-green-100 text-green-800': producto.disponible,
                        'bg-red-100 text-red-800': !producto.disponible
                    }" class="inline-block px-2 py-0.5 text-xs font-medium rounded-full mt-1">
                        <span x-text="producto.disponible ? 'Disponible' : 'No Disponible'"></span>
                    </span>
                    <span :class="{ 
                        'bg-blue-100 text-blue-800': producto.visible,
                        'bg-gray-100 text-gray-800': !producto.visible
                    }" class="inline-block px-2 py-0.5 text-xs font-medium rounded-full mt-1 ml-2">
                        <span x-text="producto.visible ? 'Visible' : 'Oculto'"></span>
                    </span>

                    <div class="mt-3">
                        <button @click="editProduct(producto)" class="px-2 py-1 bg-yellow-500 text-white rounded text-sm">
                            Editar
                        </button>
                        <button @click="showConfirmation = true; currentProduct = producto" class="px-2 py-1 bg-red-500 text-white rounded text-sm">
                            Eliminar
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <div x-show="!isLoading && productos.length === 0" class="alert alert-info my-3">
        No hay productos registrados en la base de datos.
    </div>
    
    
    <!-- Modal para Agregar/Editar Producto -->
    <div x-show="showModal" 
         x-transition
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        
        <div class="flex items-center justify-center min-h-screen">
            <div @click.away="showModal = false" class="bg-white p-8 rounded shadow-xl w-full max-w-lg mx-auto">
                <h3 class="text-xl font-bold mb-4" x-text="isEditing ? 'Editar Producto: ' + currentProduct.nombre : 'Agregar Nuevo Producto'"></h3>
                
                <form id="productForm" method="POST" enctype="multipart/form-data" 
                      :action="isEditing 
                            ? '{{ $productosUpdatePrefix }}/' + currentProduct.id 
                            : '{{ $productosStoreRoute }}'">
                    @csrf
                    <input x-show="isEditing" type="hidden" name="_method" value="PUT">
                    
                    <div class="mb-3">
                        <label for="nombre" class="block text-sm font-medium">Nombre</label>
                        <input type="text" name="nombre" class="w-full border rounded p-2 mt-1" 
                               x-model="currentProduct.nombre"
                               required>
                    </div>

                    <div class="mb-3">
                        <label for="categoria" class="block text-sm font-medium">Categoría</label>
                        <select name="categoria" class="w-full border rounded p-2 mt-1" required
                                x-model="currentProduct.categoria">
                            <template x-for="cat in categorias" :key="cat">
                                <option :value="cat" x-text="cat"></option>
                            </template>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="precio" class="block text-sm font-medium">Precio de Venta ($)</label>
                        <input type="number" step="0.01" name="precio" class="w-full border rounded p-2 mt-1" 
                               x-model.number="currentProduct.precio"
                               required min="0">
                    </div>
                    
                    <div class="mb-3">
                        <label for="costo" class="block text-sm font-medium">Costo de Compra ($ - Opcional)</label>
                        <input type="number" step="0.01" name="costo" class="w-full border rounded p-2 mt-1" 
                               x-model.number="currentProduct.costo"
                               min="0">
                    </div>
                    
                    <div class="mb-3">
                        <label for="proveedor" class="block text-sm font-medium">Proveedor (Opcional)</label>
                        <input type="text" name="proveedor" class="w-full border rounded p-2 mt-1" 
                               x-model="currentProduct.proveedor">
                    </div>
                    
                    <div class="mb-3">
                        <label for="descripcion" class="block text-sm font-medium">Descripción (Opcional)</label>
                        <textarea name="descripcion" class="w-full border rounded p-2 mt-1" 
                                  x-model="currentProduct.descripcion"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="imagen" class="block text-sm font-medium">Imagen</label>
                        <input type="file" name="imagen" class="w-full border rounded p-2 mt-1">
                        <p x-show="isEditing && currentProduct.imagen" class="text-xs text-gray-500 mt-1">
                            Imagen actual cargada. Sube una nueva para reemplazar.
                        </p>
                        <p x-show="!isEditing" class="text-xs text-red-500 mt-1">
                            La imagen es obligatoria al crear.
                        </p>
                    </div>
                    
                    <div class="flex space-x-4 mb-3">
                        <div class="flex items-center">
                            <input type="checkbox" name="disponible" id="disponible" class="h-4 w-4 text-blue-600 border-gray-300 rounded" 
                                   value="1" x-model="currentProduct.disponible">
                            <label for="disponible" class="ml-2 block text-sm">Disponible</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="visible" id="visible" class="h-4 w-4 text-blue-600 border-gray-300 rounded" 
                                   value="1" x-model="currentProduct.visible">
                            <label for="visible" class="ml-2 block text-sm">Visible al Público</label>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-2 mt-4">
                        <button type="button" @click="showModal = false" class="px-4 py-2 bg-gray-300 rounded">Cancelar</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded" 
                                x-text="isEditing ? 'Actualizar Producto' : 'Guardar Producto'"></button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Modal de Confirmación de Eliminación -->
    <div x-show="showConfirmation" 
         x-transition
         class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
         style="display: none;">
        <div class="bg-white p-6 rounded shadow-md w-96">
            <h3 class="text-lg font-bold mb-4">Eliminar Producto</h3>
            <p>¿Estás seguro de que deseas eliminar el producto: <span x-text="currentProduct.nombre" class="font-semibold"></span>?</p>
            <div class="flex justify-end space-x-2 mt-4">
                <button type="button" @click="showConfirmation = false" class="px-4 py-2 bg-gray-300 rounded">Cancelar</button>
                <form :action="'{{ $productosDestroyPrefix }}/' + currentProduct.id" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded">Sí, eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>