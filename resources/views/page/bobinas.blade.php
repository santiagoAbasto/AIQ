@extends('layouts.app')
@section('title', 'Bobinas y Láminas')
@section('content')
<style>
    /* Carousel Styles */
.carousel-item {
    height: 680px;
    background-size: cover;
    background-position: center;
    position: relative;
}

/* Overlay para añadir opacidad al carousel */
.carousel-item::before,
.carousel-video-wrapper::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
    background-color: #00000099;
}

.carousel-caption {
    position: absolute;
    top:190px;
    left: 0;
    right: 0;
    bottom: 0;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    align-items: flex-start;
    text-align: left;
    /* padding-left: 10%; */
    z-index: 2; /* Asegurar que el contenido esté por encima del overlay */
}

.carousel__titulo {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-style: 'SemiBold';
    font-size: 48px;
    font-weight: 600;
    line-height: 120%;
    margin-bottom: 1rem;
    color: #FFFFFF; /* Cambiado a blanco para mejor contraste con el overlay */

}

.carousel__descripcion {
    font-size: 1.2rem;
    color: #FFFFFF; /* Cambiado a blanco para mejor contraste con el overlay */
    max-width: 80%;
}


</style>
<div id="carouselExampleIndicators" class="carousel slide position-relative" data-bs-ride="carousel" data-bs-interval="3000" data-bs-pause="false">
    <!-- Indicadores -->
    <div class="carousel-indicators justify-content-center">
        @foreach($sliders as $index => $slider)
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }}" aria-current="{{ $index == 0 ? 'true' : 'false' }}" aria-label="Slide {{ $index + 1 }}"></button>
        @endforeach
    </div>
    {{-- miga de pan --}}
<div class="carousel-breadcrumb">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-hero mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('index') }}">Inicio</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                   Bobinas y Láminas
                </li>
            </ol>
        </nav>
    </div>
</div>

    <div class="carousel-inner">
        @foreach($sliders as $index => $slider)
            @if(Str::contains($slider->imagen, ['.mp4', '.mov', '.avi']))
                <!-- Elemento - Video -->
                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                    <div class="carousel-video-wrapper">
                        <video class="carousel-video" autoplay loop muted>
                            <source src="{{ media_url($slider->imagen) }}" type="video/mp4">
                            Tu navegador no soporta video HTML5.
                        </video>
                        <div class="carousel-caption text-left">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="carousel__titulo">{{ $slider->titulo }}</h5>
                                        <div class="carousel__descripcion">{!! $slider->descripcion !!}</div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Elemento - Imagen como background -->
                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}" style="background-image: url('{{ media_url($slider->imagen) }}');
                 height: 400px;
                ">
                    <div class="carousel-caption text-left">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="carousel__titulo">{{ $slider->titulo }}</h5>
                                    <div class="carousel__descripcion">{!! $slider->descripcion !!}</div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>

   



</div>

<div class="py-4" style="background-color:#F5F5F5;">
    <div class="container">
        <div class="row justify-content-center align-items-center text-left">
            <div class="col-md-12 col-12 text-md-left text-left mb-3 mb-md-0">
                <span class="">
                    {!! $contenidoBobina->descripcion !!}
                </span>
            </div>

          
        </div>
    </div>
</div>


<div class="container my-5">
    <div class="row">
        <style>
            .card-bobina{
                background: #fff;
                border: 1px solid #D9D9D9;
                border-radius: 10px;
                overflow: hidden;
                height: 100%;
                transition: transform .3s ease, box-shadow .3s ease;
            }

            .card-bobina:hover{
                transform: translateY(-4px);
                box-shadow: 0 10px 25px rgba(0,0,0,.08);
            }

            .card-bobina__img{
                width: 100%;
                height: 260px;
                object-fit: cover;
                display: block;
            }

            .card-bobina__body{
                padding: 24px 18px 28px;
            }

            .card-bobina__title{
                font-size: 28px;
                font-weight: 700;
                line-height: 1.2;
                color: #111;
                margin-bottom: 10px;
            }

            .card-bobina__text{
                font-size: 16px;
                color: #333;
                line-height: 1.5;
                margin-bottom: 10px;
            }

            .card-bobina__content p{
                margin-bottom: 8px;
                font-size: 16px;
                color: #333;
                line-height: 1.5;
            }


                .card-bobina__content ul{
                list-style: none;
                padding: 0;
                margin: 12px 0 0 0;
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 8px 18px;
            }

            .card-bobina__content ul li{
                position: relative;
                padding-left: 28px;
                font-size: 15px;
                color: #333;
                line-height: 1.45;
            }

            .card-bobina__content ul li::before{
                content: "";
                position: absolute;
                left: 0;
                top: 2px;
                width: 20px;
                height: 20px;
                background-repeat: no-repeat;
                background-size: 20px 20px;
                background-position: center;
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='20' height='20' viewBox='0 0 20 20' fill='none'%3E%3Cg clip-path='url(%23clip0_10950_1988)'%3E%3Cpath d='M7.50008 9.99999L9.16675 11.6667L12.5001 8.33332M18.3334 9.99999C18.3334 14.6024 14.6025 18.3333 10.0001 18.3333C5.39771 18.3333 1.66675 14.6024 1.66675 9.99999C1.66675 5.39762 5.39771 1.66666 10.0001 1.66666C14.6025 1.66666 18.3334 5.39762 18.3334 9.99999Z' stroke='%23FB0D1B' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/g%3E%3Cdefs%3E%3CclipPath id='clip0_10950_1988'%3E%3Crect width='20' height='20' fill='white'/%3E%3C/clipPath%3E%3C/defs%3E%3C/svg%3E");
            }

            .card-bobina__content strong,
            .card-bobina__content b{
                font-weight: 600;
                color: #111;
            }

            @media (max-width: 991.98px){
                .card-bobina__img{
                    height: 220px;
                }

                .card-bobina__title{
                    font-size: 24px;
                }
            }

            @media (max-width: 767.98px){
                .card-bobina__content ul{
                    grid-template-columns: 1fr;
                }

                .card-bobina__img{
                    height: 200px;
                }

                .card-bobina__title{
                    font-size: 22px;
                }
            }
        </style>

    

        <div class="row">
            @foreach($bobinas as $bobina)
                <div class="col-12 col-md-6 mb-4">
                    <div class="card-bobina">
                        <img
                            src="{{ media_url($bobina->imagen) }}"
                            alt="{{ $bobina->titulo }}"
                            class="card-bobina__img"
                        >

                        <div class="card-bobina__body">
                            <h3 class="card-bobina__title">{{ $bobina->titulo }}</h3>

                            <div class="card-bobina__content">
                                {!! $bobina->descripcion !!}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>


@endsection
