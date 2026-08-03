<?php
use App\Http\Controllers\admin\FormularioContactoController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [App\Http\Controllers\PageController::class, 'index'])->name('index');
Route::get('/empresa', [App\Http\Controllers\PageController::class, 'empresa'])->name('empresa');
Route::get('/contacto', [App\Http\Controllers\PageController::class, 'contacto'])->name('contacto');

// Categorías and Products
Route::get('/categorias', [App\Http\Controllers\PageController::class, 'categorias'])->name('categorias');
Route::get('/productos/{catSlug}/{subSlug}', [App\Http\Controllers\PageController::class, 'productosPorSubcategoria'])->name('productos.subcategoria');
Route::get('/productos/{slug?}', [App\Http\Controllers\PageController::class, 'productos'])->name('productos');

// paletas
Route::get('/paletas', [App\Http\Controllers\PageController::class, 'paletas'])->name('paletas');

// Ruta del buscador
Route::get('/buscador', [App\Http\Controllers\PageController::class, 'search'])->name('buscador');

// termoformados
Route::get('/termoformados', [App\Http\Controllers\PageController::class, 'termoformados'])->name('termoformados');

// trabajos
Route::get('/bobinas', [App\Http\Controllers\PageController::class, 'bobinas'])->name('bobinas');

Route::get('/novedades', [App\Http\Controllers\PageController::class, 'novedades'])->name('novedades');
Route::get('/novedad/{id}', [App\Http\Controllers\PageController::class, 'novedad'])->name('novedad');

// newsletter
Route::post('/newsletter/subscribe', [App\Http\Controllers\PageController::class, 'subscribeNewsletter'])->name('newsletter.subscribe');

Route::post('/contacto/send', [App\Http\Controllers\PageController::class, 'sendContactoMail'])->name('contacto.send');
Route::get('/presupuesto', [App\Http\Controllers\PageController::class, 'presupuesto'])->name('presupuesto');
Route::post('/presupuesto/send', [App\Http\Controllers\PageController::class, 'sendPresupuestoMail'])->name('presupuesto.send');

Route::get('/zona-clientes', [App\Http\Controllers\Cliente\ClienteAuthController::class, 'landing'])->name('clientes');
Route::prefix('zona-clientes')->name('cliente.')->group(function () {
    Route::get('/csrf-token', [App\Http\Controllers\Cliente\ClienteAuthController::class, 'csrfToken'])->name('csrf-token');
    Route::get('/login', [App\Http\Controllers\Cliente\ClienteAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [App\Http\Controllers\Cliente\ClienteAuthController::class, 'login'])->name('login.store');
    Route::get('/registro', [App\Http\Controllers\Cliente\ClienteAuthController::class, 'showRegister'])->name('register');
    Route::post('/registro', [App\Http\Controllers\Cliente\ClienteAuthController::class, 'register'])->name('register.store');

    Route::middleware('logincliente')->group(function () {
        Route::get('/panel', [App\Http\Controllers\Cliente\ClienteDashboardController::class, 'dashboard'])->name('dashboard');
        Route::post('/logout', [App\Http\Controllers\Cliente\ClienteAuthController::class, 'logout'])->name('logout');
        Route::get('/asesor', [App\Http\Controllers\Cliente\ClienteDashboardController::class, 'advisor'])->name('advisor');
        Route::get('/asistente/{type}', [App\Http\Controllers\Cliente\ClienteDashboardController::class, 'assistant'])->whereIn('type', ['tecnico'])->name('assistant');
        Route::post('/asistente/{type}', [App\Http\Controllers\Cliente\ClienteDashboardController::class, 'ask'])->whereIn('type', ['tecnico'])->name('assistant.ask');
        Route::delete('/asistente/{type}/chats/{chat}', [App\Http\Controllers\Cliente\ClienteDashboardController::class, 'destroyChat'])->whereIn('type', ['tecnico'])->name('assistant.chat.destroy');
    });
});

// sitemap
Route::get('/sitemap', [App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap.index');

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::prefix('admin')->name('admin.')->group(function () {
        // Dashboard route
        Route::get('/dashboard', [App\Http\Controllers\admin\AdmController::class, 'dashboard'])->name('dashboard');
    
        // Slider routes
        Route::prefix('slider')->name('slider.')->group(function () {
            Route::get('{seccion}', [App\Http\Controllers\admin\SliderController::class, 'index'])->name('index');
            Route::get('{seccion}/create', [App\Http\Controllers\admin\SliderController::class, 'create'])->name('create');
            Route::post('{seccion}/store', [App\Http\Controllers\admin\SliderController::class, 'store'])->name('store');
            Route::get('{seccion}/edit/{id}', [App\Http\Controllers\admin\SliderController::class, 'edit'])->name('edit');
            Route::put('{seccion}/update/{id}', [App\Http\Controllers\admin\SliderController::class, 'update'])->name('update');
            Route::delete('destroy/{id}', [App\Http\Controllers\admin\SliderController::class, 'destroy'])->name('destroy');
        });
    
        // Logos routes
        Route::prefix('logos')->name('logos.')->group(function () {
            Route::get('/edit/{id}', [App\Http\Controllers\admin\LogoController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [App\Http\Controllers\admin\LogoController::class, 'update'])->name('update');
        });
    
        // Inicio routes
        Route::prefix('inicio')->name('inicio.')->group(function () {
            Route::get('/edit/{id}', [App\Http\Controllers\admin\InicioController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [App\Http\Controllers\admin\InicioController::class, 'update'])->name('update');
        });
        // contenido bobina routes
        Route::prefix('contenido_bobina')->name('contenido_bobina.')->group(function () {
            Route::get('/edit/{id}', [App\Http\Controllers\admin\ContenidoBobinaController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [App\Http\Controllers\admin\ContenidoBobinaController::class, 'update'])->name('update');
        });

        // Empresa routes
        Route::prefix('empresa')->name('empresa.')->group(function () {
            Route::get('/edit/{id}', [App\Http\Controllers\admin\EmpresaController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [App\Http\Controllers\admin\EmpresaController::class, 'update'])->name('update');
            Route::delete('/eliminar-imagen/{id}/{imagen}', [App\Http\Controllers\admin\EmpresaController::class, 'eliminarImagen'])->name('eliminarImagen');
        });
      
        // Contacto routes
        Route::prefix('contacto')->name('contacto.')->group(function () {
            Route::get('/edit/{id}', [App\Http\Controllers\admin\ContactoController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [App\Http\Controllers\admin\ContactoController::class, 'update'])->name('update');
        });
    
        // Redes routes
        Route::prefix('redes')->name('redes.')->group(function () {
            Route::get('/edit/{id}', [App\Http\Controllers\admin\RedeController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [App\Http\Controllers\admin\RedeController::class, 'update'])->name('update');
        });

        // termoformados routes
        Route::prefix('termoformados')->name('termoformados.')->group(function () {

            Route::get('/edit/{id}', [App\Http\Controllers\admin\TermoformadoController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [App\Http\Controllers\admin\TermoformadoController::class, 'update'])->name('update');
            Route::delete('destroy/{id}', [App\Http\Controllers\admin\TermoformadoController::class, 'destroy'])->name('destroy');

          });   


        //  termoformados
        Route::prefix('termoformados')->name('termoformados.')->group(function () {
       
            Route::get('/edit/{id}', [App\Http\Controllers\admin\TermoformadoController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [App\Http\Controllers\admin\TermoformadoController::class, 'update'])->name('update');
            Route::delete('destroy/{id}', [App\Http\Controllers\admin\TermoformadoController::class, 'destroy'])->name('destroy');
            Route::delete('eliminar-imagen/{id}/{key}', [App\Http\Controllers\admin\TermoformadoController::class, 'eliminarImagen'])->name('eliminarImagen');
        });
      

        Route::prefix('novedades')->name('novedades.')->group(function () {
            Route::get('/', [App\Http\Controllers\admin\NovedadesController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\admin\NovedadesController::class, 'create'])->name('create');
            Route::post('/store', [App\Http\Controllers\admin\NovedadesController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [App\Http\Controllers\admin\NovedadesController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [App\Http\Controllers\admin\NovedadesController::class, 'update'])->name('update');
            Route::delete('destroy/{id}', [App\Http\Controllers\admin\NovedadesController::class, 'destroy'])->name('destroy');
            Route::delete('eliminar-imagen/{id}/{key}', [App\Http\Controllers\admin\NovedadesController::class, 'eliminarImagen'])->name('admin.novedades.eliminarImagen');
        });


        // bobinas
        Route::prefix('bobinas')->name('bobinas.')->group(function () {
            Route::get('/', [App\Http\Controllers\admin\BobinaController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\admin\BobinaController::class, 'create'])->name('create');
            Route::post('/store', [App\Http\Controllers\admin\BobinaController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [App\Http\Controllers\admin\BobinaController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [App\Http\Controllers\admin\BobinaController::class, 'update'])->name('update');
            Route::delete('destroy/{id}', [App\Http\Controllers\admin\BobinaController::class, 'destroy'])->name('destroy');
        });



       
          // categorias
        Route::prefix('categorias')->name('categorias.')->group(function () {
            Route::get('/', [App\Http\Controllers\admin\CategoriasController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\admin\CategoriasController::class, 'create'])->name('create');
            Route::post('/store', [App\Http\Controllers\admin\CategoriasController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [App\Http\Controllers\admin\CategoriasController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [App\Http\Controllers\admin\CategoriasController::class, 'update'])->name('update');
            Route::delete('destroy/{id}', [App\Http\Controllers\admin\CategoriasController::class, 'destroy'])->name('destroy');
        });

        // Subcategorias routes
        Route::prefix('subcategorias')->name('subcategorias.')->group(function () {
            Route::get('/', [App\Http\Controllers\admin\SubcategoriasController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\admin\SubcategoriasController::class, 'create'])->name('create');
            Route::post('/store', [App\Http\Controllers\admin\SubcategoriasController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [App\Http\Controllers\admin\SubcategoriasController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [App\Http\Controllers\admin\SubcategoriasController::class, 'update'])->name('update');
            Route::delete('destroy/{id}', [App\Http\Controllers\admin\SubcategoriasController::class, 'destroy'])->name('destroy');
        });


        // caracteristicas routes
        Route::prefix('caracteristicas')->name('caracteristicas.')->group(function () {
            Route::get('/', [App\Http\Controllers\admin\CaracteristicaController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\admin\CaracteristicaController::class, 'create'])->name('create');
            Route::post('/store', [App\Http\Controllers\admin\CaracteristicaController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [App\Http\Controllers\admin\CaracteristicaController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [App\Http\Controllers\admin\CaracteristicaController::class, 'update'])->name('update');
            Route::delete('destroy/{id}', [App\Http\Controllers\admin\CaracteristicaController::class, 'destroy'])->name('destroy');
        });

          // Productos routes
        Route::prefix('productos')->name('productos.')->group(function () {
            Route::get('/', [App\Http\Controllers\admin\ProductoController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\admin\ProductoController::class, 'create'])->name('create');
            Route::post('/store', [App\Http\Controllers\admin\ProductoController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [App\Http\Controllers\admin\ProductoController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [App\Http\Controllers\admin\ProductoController::class, 'update'])->name('update');
            Route::delete('destroy/{id}', [App\Http\Controllers\admin\ProductoController::class, 'destroy'])->name('destroy');
            Route::delete('eliminar-imagen/{id}/{key}', [App\Http\Controllers\admin\ProductoController::class, 'eliminarImagen'])->name('eliminarImagen');
        });

      
        // newsletter routes
        Route::prefix('newsletter')->name('newsletter.')->group(function () {
            Route::get('/', [App\Http\Controllers\admin\NewsletterController::class, 'index'])->name('index');
            Route::delete('destroy/{id}', [App\Http\Controllers\admin\NewsletterController::class, 'destroy'])->name('destroy');
        }); 

        Route::prefix('clientes')->name('clientes.')->group(function () {
            Route::get('/', [App\Http\Controllers\admin\ClienteZonaController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\admin\ClienteZonaController::class, 'create'])->name('create');
            Route::post('/store', [App\Http\Controllers\admin\ClienteZonaController::class, 'store'])->name('store');
            Route::get('/edit/{cliente}', [App\Http\Controllers\admin\ClienteZonaController::class, 'edit'])->name('edit');
            Route::put('/update/{cliente}', [App\Http\Controllers\admin\ClienteZonaController::class, 'update'])->name('update');
            Route::delete('/destroy/{cliente}', [App\Http\Controllers\admin\ClienteZonaController::class, 'destroy'])->name('destroy');
            Route::get('/imports/{cliente}', [App\Http\Controllers\admin\ClienteZonaController::class, 'imports'])->name('imports');
            Route::post('/imports/{cliente}', [App\Http\Controllers\admin\ClienteZonaController::class, 'importClientes'])->name('imports.store');
            Route::get('/asistentes', [App\Http\Controllers\admin\ClienteZonaController::class, 'aiRequests'])->name('ai');
            Route::get('/base-conocimiento', [App\Http\Controllers\admin\ClienteZonaController::class, 'knowledge'])->name('knowledge');
            Route::post('/base-conocimiento', [App\Http\Controllers\admin\ClienteZonaController::class, 'storeKnowledge'])->name('knowledge.store');
            Route::delete('/base-conocimiento/{document}', [App\Http\Controllers\admin\ClienteZonaController::class, 'destroyKnowledge'])->name('knowledge.destroy');
        });

        Route::get('/integraciones-ia', [App\Http\Controllers\admin\AiIntegrationController::class, 'edit'])->name('integrations.edit');
        Route::put('/integraciones-ia', [App\Http\Controllers\admin\AiIntegrationController::class, 'update'])->name('integrations.update');
      

        // Users routes
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [App\Http\Controllers\admin\UserController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\admin\UserController::class, 'create'])->name('create');
            Route::post('/store', [App\Http\Controllers\admin\UserController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [App\Http\Controllers\admin\UserController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [App\Http\Controllers\admin\UserController::class, 'update'])->name('update');
            Route::delete('destroy/{id}', [App\Http\Controllers\admin\UserController::class, 'destroy'])->name('destroy');
        });
    
        Route::prefix('metadatos')->name('metadatos.')->group(function () {
            Route::get('/', [App\Http\Controllers\admin\MetadataController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\admin\MetadataController::class, 'create'])->name('create');
            Route::post('/store', [App\Http\Controllers\admin\MetadataController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [App\Http\Controllers\admin\MetadataController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [App\Http\Controllers\admin\MetadataController::class, 'update'])->name('update');
            Route::delete('destroy/{id}', [App\Http\Controllers\admin\MetadataController::class, 'destroy'])->name('destroy');
        });

        // Contacto Mensajes
        Route::resource('contactomensaje', FormularioContactoController::class);
    });
});

// Include authentication routes
require __DIR__.'/auth.php';

Route::get('/producto/{slug}', [App\Http\Controllers\PageController::class, 'producto'])->name('producto');
