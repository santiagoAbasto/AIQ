<?php

namespace App\Http\Controllers;
use App\Models\Inicio;
use App\Models\Empresa;
use App\Models\Contacto;
use App\Models\Logo;
use App\Models\Rede;
use App\Models\Slider;
use App\Models\Producto;
use App\Models\Subcategoria;
use App\Models\Categoria;
use App\Models\RelacionProducto;
use App\Models\Termoformado;
use App\Models\ContenidoBobina;

use App\Models\Novedades;
use App\Models\Calidad;
use App\Models\Descarga;
use App\Models\Equipo;
use App\Models\Bobina;
use App\Models\FormularioContacto;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactoMail;
use App\Mail\PresupuestoMail;
use App\Mail\NewsletterSubscriptionMail;
use App\Models\Newsletter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Models\Metadata;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{

    /**
     * Resuelve un slug buscando en Producto, Equipo y Trabajo.
     */
    public function resolveSlug($slug)
    {
        if (Producto::where('slug', $slug)->exists()) {
            return $this->producto($slug);
        }
        if (Equipo::where('slug', $slug)->exists()) {
            return $this->equipo($slug);
        }
      
        abort(404);
    }

    public function index(Request $request){
    // Obtener los datos de los modelos

    $logo = Logo::first();
    $inicio = Inicio::first();
    $redes = Rede::first();
    // $servicios = Servicio::orderBy('orden', 'asc')->take(4)->get();
    
     $novedades = Novedades::orderBy('orden', 'asc')->take(3)->get();
    $productos = Producto::orderBy('orden', 'asc')->get();
    $categorias = Categoria::orderBy('orden', 'asc')->get();
    $contacto = Contacto::first(); // Si sólo hay un contacto, puedes usar first()
    $sliders = Slider::where('seccion', 'inicio')->orderBy('orden', 'asc')->get();
    $metadata = Metadata::where('section', 'inicio')->first();

    // Pasar los datos a la vista
    return view('page.index', compact('inicio', 'redes', 'contacto', 'sliders', 'logo', 'productos', 'novedades', 'metadata', 'categorias'  ));
    }


    public function empresa(){
    // Obtener los datos de los modelos
    $logo = Logo::first();
    $empresa = Empresa::first();
    // $servicios = Servicio::orderBy('orden', 'asc')->get();
     $categorias = Categoria::orderBy('orden', 'asc')->get();
    $redes = Rede::first();
    $contacto = Contacto::first(); // Si sólo hay un contacto, puedes usar first()
    $sliders = Slider::where('seccion', 'empresa')->orderBy('orden', 'asc')->get();
    $metadata = Metadata::where('section', 'empresa')->first();

    // Pasar los datos a la vista
    return view('page.empresa', compact('empresa', 'redes', 'contacto', 'logo', 'sliders', 'metadata', 'categorias'));

    }



    // bobinas 
    public function bobinas(){
        // Obtener los datos de los modelos
        $logo = Logo::first();
        $redes = Rede::first();
        $contacto = Contacto::first(); // Si sólo hay un contacto, puedes usar first()
        $sliders = Slider::where('seccion', 'bobinas')->get();
        $metadata = Metadata::where('section', 'bobinas')->first();    
        $contenidoBobina = ContenidoBobina::first();
        $bobinas = Bobina::orderBy('orden', 'asc')->get();
        // Pasar los datos a la vista
        return view('page.bobinas', compact('redes', 'contacto', 'logo', 'sliders', 'metadata', 'contenidoBobina', 'bobinas'));
    }


    
    // buscador de productos 
  
    public function search(Request $request)
    {
        // Obtenemos el término de búsqueda desde el request
        $query = $request->input('search');
        $logo = Logo::first();
        $redes = Rede::first();
        $contacto = Contacto::first();
        $categorias = Categoria::with('subcategorias')->orderBy('orden', 'asc')->get();
        $metadata = Metadata::where('section', 'busqueda')->first();

        // Si hay un término de búsqueda, realizamos la consulta
        if ($query) {
            $productos = Producto::where('titulo', 'LIKE', "%$query%")
                ->orWhere('descripcion', 'LIKE', "%$query%")
                 ->orWhereHas('relaciones.categoria', function ($q) use ($query) {
                    $q->where('titulo', 'LIKE', "%$query%");
                })
                ->orWhereHas('relaciones.subcategoria', function ($q) use ($query) {
                    $q->where('titulo', 'LIKE', "%$query%");
                })
                ->orderBy('orden', 'asc')
                ->get();
        } else {
            // Si no hay búsqueda, traemos todos los productos
            $productos = Producto::all();
        }

        // Retornar los productos a la vista
        return view('page.resultado', compact('productos', 'logo', 'redes', 'contacto', 'metadata', 'categorias', 'query'));
    }
    

// categoria products
    public function productos($slug = null)
    {
        $logo = Logo::first();
        $redes = Rede::first();
        $contacto = Contacto::first();

        // Sidebar: categorías con sus subcategorías anidadas
        $categorias = Categoria::with('subcategorias')->orderBy('orden', 'asc')->get();

        $categoria    = null;
        $subcategoria = null;
        $productosQuery = Producto::with('relaciones.categoria', 'relaciones.subcategoria')->orderBy('orden', 'asc');

        if ($slug) {
            // Solo buscar como categoría padre (las subcategorías usan la ruta /productos/{cat}/{sub})
            $categoria = Categoria::with('subcategorias')->where('slug', $slug)->firstOrFail();
            $subIds = $categoria->subcategorias->pluck('id');
            $productosQuery->whereHas('relaciones', function ($q) use ($categoria, $subIds) {
                $q->where('categoria_id', $categoria->id);
                if ($subIds->isNotEmpty()) {
                    $q->orWhereIn('subcategoria_id', $subIds);
                }
            });
        }

        $productos = $productosQuery->get();
        $sliders   = Slider::where('seccion', 'productos')->get();
        $metadata  = Metadata::where('section', 'categorias')->first();

        return view('page.productos', compact(
            'categoria',
            'subcategoria',
            'logo',
            'redes',
            'contacto',
            'sliders',
            'metadata',
            'categorias',
            'productos',
        ));
    }

    // productos filtrados por subcategoría (slug de cat + slug de sub)
    public function productosPorSubcategoria($catSlug, $subSlug)
    {
        $logo = Logo::first();
        $redes = Rede::first();
        $contacto = Contacto::first();

        $categorias = Categoria::with('subcategorias')->orderBy('orden', 'asc')->get();
        $categoria  = Categoria::with('subcategorias')->where('slug', $catSlug)->firstOrFail();
        $subcategoria = Subcategoria::where('slug', $subSlug)
            ->where('categoria_id', $categoria->id)
            ->firstOrFail();

        $productos = Producto::with('relaciones.categoria', 'relaciones.subcategoria')
            ->whereHas('relaciones', function ($q) use ($subcategoria) {
                $q->where('subcategoria_id', $subcategoria->id);
            })
            ->orderBy('orden', 'asc')
            ->get();

        $sliders  = Slider::where('seccion', 'productos')->get();
        $metadata = Metadata::where('section', 'categorias')->first();

        return view('page.productos', compact(
            'categoria',
            'subcategoria',
            'logo',
            'redes',
            'contacto',
            'sliders',
            'metadata',
            'categorias',
            'productos',
        ));
    }

    // producto detail
    public function producto($slug)
    {
        $logo = Logo::first();
        $empresa = Empresa::first();
        $redes = Rede::first();
        $contacto = Contacto::first();
        $sliders = Slider::where('seccion', 'productos')->get();

        // Cargar producto con sus relaciones (categoria + subcategoria)
        $producto = Producto::with('relaciones.categoria', 'relaciones.subcategoria')->where('slug', $slug)->firstOrFail();

        // Para marcar activo en sidebar
        $primeraRelacion          = $producto->relaciones->first();
        $subcategoriaSeleccionada = $primeraRelacion?->subcategoria;
        $categoriaSeleccionada    = $primeraRelacion?->categoria;

        $categorias = Categoria::with('subcategorias')->orderBy('orden', 'asc')->get();
        $metadata = Metadata::where('section', 'productos')->first();
        
        return view('page.producto', compact('empresa', 'redes', 'contacto', 'logo', 'producto', 'sliders', 'metadata','categorias', 'categoriaSeleccionada'));
    }
            

    // paleta de colores
        public function paletas(){
            // Obtener los datos de los modelos
            $logo = Logo::first();
            $redes = Rede::first();
            $contacto = Contacto::first(); // Si sólo hay un contacto, puedes usar first()
            $sliders = Slider::where('seccion', 'paletas')->get();
            $metadata = Metadata::where('section', 'paleta')->first();

            // necesito obterner todas las subcategorias de colores para mostrar en la paleta y
            //  ademas los productos de cada subcategoria para mostrar en la paleta de colores,
            //  para eso necesito obtener las relaciones de cada subcategoria 
            // con los productos relacionados y mostrar esos productos relacionados en la vista de la paleta de colores
            $categorias = Categoria::with('subcategorias')->orderBy('orden', 'asc')->get();
            $paletas = Subcategoria::with('productos')
                ->whereHas('categoria', function($q){
                    $q->where('slug', 'colores');
                })->orderBy('orden', 'asc')->get();
                

           
            // Pasar los datos a la vista
            return view('page.paletas', compact('redes', 'contacto', 'logo', 'sliders', 'metadata', 'categorias', 'paletas'));
        }

        
    public function novedades(){
        // Obtener los datos de los modelos
        $logo = Logo::first();
        $redes = Rede::first();
        $sliders = Slider::where('seccion', 'novedades')->get();
        
               $categorias = Categoria::orderBy('orden', 'asc')->get();
        $contacto = Contacto::first(); // Si sólo hay un contacto, puedes usar first()
        $novedades = Novedades::orderBy('orden', 'asc')->get();
        $metadata = Metadata::where('section', 'novedades')->first();
        
        // Pasar los datos a la vista
        return view('page.novedades', compact('redes', 'contacto', 'logo', 'novedades', 'sliders', 'metadata', 'categorias'));
    }
    
    public function novedad($id){
        // Obtener los datos de los modelos
        $logo = Logo::first();
        $redes = Rede::first();
        $sliders = Slider::where('seccion', 'novedades')->get();
        
               $categorias = Categoria::orderBy('orden', 'asc')->get();
        $contacto = Contacto::first(); // Si sólo hay un contacto, puedes usar first()
        $novedad = Novedades::find($id);
        $metadata = Metadata::where('section', 'novedades')->first();
        
        // Pasar los datos a la vista
        return view('page.novedad', compact('redes', 'contacto', 'logo', 'novedad', 'sliders', 'metadata', 'categorias'));
    }
   
       public function termoformados(){
    // Obtener los datos de los modelos
        $logo = Logo::first();
        $termoformado = Termoformado::find(1); 
        $redes = Rede::first();
        $contacto = Contacto::first(); // Si sólo hay un contacto, puedes usar first()
        $sliders = Slider::where('seccion', 'termoformados')->get();
        $metadata = Metadata::where('section', 'termoformados')->first();
    

    // Pasar los datos a la vista
    return view('page.termoformados', compact('termoformado', 'redes', 'contacto', 'logo', 'sliders', 'metadata'));

    }




    public function contacto(){
        // Obtener los datos de los modelos
        $logo = Logo::first();
        $redes = Rede::first();
        $contacto = Contacto::first(); // Si sólo hay un contacto, puedes usar first()
        $sliders = Slider::where('seccion', 'contacto')->get();
       
        $metadata = Metadata::where('section', 'contacto')->first();
           $categorias = Categoria::orderBy('orden', 'asc')->get();
        // Pasar los datos a la vista
        return view('page.contacto', compact('redes', 'contacto', 'logo', 'sliders', 'metadata', 'categorias'));
    }

    public function presupuesto(){
        // Obtener los datos de los modelos
        $logo = Logo::first();
        $redes = Rede::first();
        $contacto = Contacto::first(); // Si sólo hay un contacto, puedes usar first()
        $metadata = Metadata::where('section', 'presupuesto')->first();
     $sliders = Slider::where('seccion', 'presupuesto')->get();
        // Pasar los datos a la vista
        return view('page.presupuesto', compact('redes', 'contacto', 'logo', 'metadata', 'sliders'));
    }


// contacto

  public function sendContactoMail(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'surname' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'message' => 'nullable',
            'g-recaptcha-response' => 'required'
        ]);
        // Verificar reCAPTCHA
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => env('RECAPTCHA_SECRET_KEY'),
            'response' => $request->input('g-recaptcha-response')
        ])->object();
    
        if (!$response || !isset($response->success) || !$response->success || $response->score < 0.7) {
            $errorMsg = 'No se pudo verificar reCAPTCHA';
            if (isset($response->{'error-codes'})) {
                $errorMsg .= ': ' . implode(', ', $response->{'error-codes'});
            }
            Log::warning('Verificación reCAPTCHA fallida: ' . json_encode($response));
            return response()->json(['error' => $errorMsg], 422);
        }
        try {
            // Guardar los datos del formulario en la base de datos
            $contactoMensaje = FormularioContacto::create([
                'name' => $validated['name'],
                'surname' => $validated['surname'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'message' => $validated['message'],
                'email_sent' => false
            ]);        
            // Intentar enviar el correo electrónico
            try {
                Mail::to('gigliottilucas4@gmail.com')->send(new ContactoMail($validated));
                // Actualizar el registro para indicar que el email fue enviado
                $contactoMensaje->update(['email_sent' => true]);
            } catch (\Exception $e) {
                Log::error('Error al enviar el correo electrónico: ' . $e->getMessage());
                // No devolvemos error, ya que los datos están guardados en la base de datos
            }
            
            return response()->json(['message' => 'Mensaje recibido exitosamente. Nos pondremos en contacto pronto.']);
        } catch (\Exception $e) {
            Log::error('Error al procesar el formulario de contacto: ' . $e->getMessage());
            return response()->json(['error' => 'Error al procesar su mensaje. Por favor, inténtelo de nuevo más tarde.'], 500);
        }
    }

    
    public function sendPresupuestoMail(Request $request)
    {
        //  dd($request->all());
     
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => env('RECAPTCHA_SECRET_KEY'),
            'response' => $request->input('g-recaptcha-response')
        ])->object();
        
        if ($response->success && $response->score >= 0.7) {
      
      
            try {
                $validated = $request->validate([
                    'name' => 'required',
                    'email' => 'required|email',
                    'phone' => 'required',
                    'message' => 'required',
                    // Validación para array de archivos
                    'archivo' => 'nullable',
                    'archivo.*' => 'file|max:10240', 
                    // Nuevos campos del diseño
                    'medidas' => 'nullable|string',
                    'uso' => 'nullable|string',
                   
                ]);
                $filePaths = [];
                if($request->hasFile('archivo')){
                    foreach($request->file('archivo') as $file){
                         // Guardar cada archivo y agregar path al array
                        $filePaths[] = $file->store('presupuestos');
                    }
                }
                // Enviar el correo con los archivos adjuntos (array)
                Mail::to('gigliottilucas4@gmail.com')->send(new PresupuestoMail($validated, $filePaths));
    
                return response()->json(['message' => 'Presupuesto enviado exitosamente.']);
    
            } catch (\Exception $e) {
                // Capturar y manejar cualquier excepción que ocurra durante el proceso
                return response()->json(['error' => 'Error al enviar el mensaje. ' . $e->getMessage()], 500);
            }
        } 
        /*
        else {
            return response()->json(['error' => 'No se pudo enviar la solicitud (Recaptcha)']);
        }
        */
    }


  public function subscribeNewsletter(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email:rfc,dns|max:255',
        ]);

        $newsletter = Newsletter::firstOrCreate([
            'email' => strtolower($validated['email']),
        ]);

            if ($newsletter->wasRecentlyCreated) {
                $contacto = Contacto::first();

                if (!empty($contacto?->correo)) {
                    try {
                        Mail::to($contacto->correo)->send(new NewsletterSubscriptionMail($newsletter));
                    } catch (\Exception $e) {
                        Log::error('Error al enviar notificacion de newsletter: ' . $e->getMessage());
                    }
                } else {
                    Log::warning('No se envio la notificacion de newsletter porque no hay correo de contacto configurado.');
                }
            }

        $message = $newsletter->wasRecentlyCreated
            ? __('Gracias por suscribirte a nuestro newsletter.')
            : __('Este correo ya está registrado.');

        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message,
                'created' => $newsletter->wasRecentlyCreated,
            ]);
        }

        return back()->with('newsletter_status', $message);
    }
    
    
}
