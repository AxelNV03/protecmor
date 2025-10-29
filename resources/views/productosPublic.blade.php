{{-- resources/views/productosPublic.blade.php --}}

@extends('layouts.app') {{-- Asume un layout base para el sitio público --}}

@section('styles')
    <style>
        .product-card-public {
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 25px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            transition: transform 0.3s, box-shadow 0.3s;
            background-color: #fff;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .product-card-public:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
        }
        .product-image-public {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 15px;
        }
        .card-body-public {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        .card-title-public {
            font-size: 1.25rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        .card-price-public {
            font-size: 1.5rem;
            color: #007bff; /* Color primario */
            margin-bottom: 10px;
        }
        .card-description-public {
            color: #666;
            margin-bottom: 15px;
            flex-grow: 1;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2; /* Limita a 2 líneas */
            -webkit-box-orient: vertical;
        }
        .card-category-public {
            font-size: 0.85rem;
            color: #999;
            margin-bottom: 15px;
        }
        .whatsapp-link {
            margin-top: auto; /* Empuja el botón al fondo */
        }
    </style>
@endsection

@section('content')
    <div class="container py-5">
        <h1 class="text-center mb-5">Productos Disponibles 🛒</h1>

        <div class="card mb-5 p-3">
            <form method="GET" action="{{ route('productos.public.index') }}" class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label for="categoria" class="form-label">Filtrar por Categoría</label>
                    <select name="categoria" class="form-select">
                        <option value="">Todas las Categorías</option>
                        @foreach ($categorias as $cat)
                            <option value="{{ $cat }}" {{ request('categoria') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-5">
                    <label for="ordenar_precio" class="form-label">Ordenar por Precio</label>
                    <select name="ordenar_precio" class="form-select">
                        <option value="">Por Nombre</option>
                        <option value="asc" {{ request('ordenar_precio') == 'asc' ? 'selected' : '' }}>Precio: Más bajo primero</option>
                        <option value="desc" {{ request('ordenar_precio') == 'desc' ? 'selected' : '' }}>Precio: Más alto primero</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filtrar/Ordenar</button>
                </div>
            </form>
        </div>

        <div class="row">
            @forelse ($productos as $producto)
                <div class="col-md-4 mb-4">
                    <div class="product-card-public">
                        <img src="{{ $producto->imagen ?? asset('images/default-product.png') }}" class="product-image-public" alt="{{ $producto->nombre }}">
                        <div class="card-body-public">
                            <h5 class="card-title-public">{{ $producto->nombre }}</h5>
                            <p class="card-price-public">${{ number_format($producto->precio, 2) }}</p>
                            <p class="card-category-public">Categoría: {{ $producto->categoria }}</p>
                            <p class="card-description-public">{{ Str::limit($producto->descripcion ?? 'Sin descripción.', 80) }}</p>
                            
                            {{-- Botón Adquirir con enlace de WhatsApp [cite: 146]--}}
                            @php
                                $whatsapp_message = urlencode("Hola buen día. Me interesa adquirir el producto: " . $producto->nombre);
                                // Reemplazar con el número real de PROTECMOR
                                $whatsapp_number = '5219991234567'; 
                            @endphp

                            <a href="https://wa.me/{{ $whatsapp_number }}?text={{ $whatsapp_message }}" 
                               target="_blank" 
                               class="btn btn-success whatsapp-link">
                                <i class="fab fa-whatsapp"></i> Adquirir
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <p class="text-center">Actualmente no hay productos disponibles para su adquisición.</p>
                </div>
            @endforelse
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $productos->links() }}
        </div>
    </div>
@endsection