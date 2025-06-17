<?php // Este es un comentario que indica un archivo PHP, el contenido real del archivo irá debajo.

// --- INSTRUCCIONES GENERALES PARA CONFIGURAR TU PROYECTO LARAVEL ---
//
// 1.  Instalar Composer: Asegúrate de tener Composer instalado. Es el gestor de dependencias de PHP.
//     https://getcomposer.org/download/
//
// 2.  Instalar Laravel Installer (opcional, pero útil):
//     composer global require laravel/installer
//
// 3.  Crear un nuevo proyecto Laravel (ejecutar en tu terminal):
//     laravel new automarket-backend
//     cd automarket-backend
//
// 4.  Configurar el archivo .env (VER ABAJO)
//
// 5.  Crear las Migraciones (archivos de base de datos)
//     php artisan make:migration create_vehicles_table
//     php artisan make:migration create_vehicle_images_table
//     php artisan make:migration create_customization_settings_table
//
// 6.  Ejecutar las Migraciones (para crear las tablas en la DB):
//     php artisan migrate
//
// 7.  Crear los Modelos:
//     php artisan make:model Vehicle
//     php artisan make:model VehicleImage
//     php artisan make:model CustomizationSetting
//
// 8.  Crear los Controladores para la API:
//     php artisan make:controller Api/VehicleController --api
//     php artisan make:controller Api/CustomizationController --api
//
// 9.  Configurar CORS (para que el frontend pueda comunicarse con el backend).
//     Abre 'config/cors.php' y 'app/Http/Kernel.php' y realiza los cambios indicados abajo.
//
// 10. Modificar rutas (routes/api.php) y lógica de controladores (app/Http/Controllers/Api/).

// --- CONTENIDO DE ARCHIVOS CLAVE ---

// --- ARCHIVO: .env (en la raíz de tu proyecto Laravel) ---
// Modifica este archivo con la configuración de tu base de datos.
// Si ya tienes un archivo .env, solo necesitas modificar las líneas relevantes de la base de datos.
// No incluyo aquí todas las líneas del .env, solo las de la DB.

/*
APP_NAME="AutoMarket Backend"
APP_ENV=local
APP_KEY=base64:YOUR_GENERATED_APP_KEY_HERE (se genera automáticamente al crear el proyecto)
APP_DEBUG=true
APP_URL=http://localhost:8000 (o la URL de tu backend, ej: https://admin.automarket.socialmarketinglatino.site)

LOG_CHANNEL=stack
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1 // O la IP de tu servidor de base de datos
DB_PORT=3306
DB_DATABASE=automarket_db // El nombre de tu base de datos
DB_USERNAME=root // Tu usuario de base de datos
DB_PASSWORD= // Tu contraseña de base de datos

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

VITE_APP_NAME="${APP_NAME}"
VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="${PUSHER_HOST}"
VITE_PUSHER_PORT="${PUSHER_PORT}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
*/

// --- ARCHIVO: database/migrations/YYYY_MM_DD_HHMMSS_create_vehicles_table.php ---
// (El nombre del archivo tendrá una marca de tiempo generada automáticamente)

namespace Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('brand');
            $table->string('model');
            $table->integer('year');
            $table->decimal('price', 15, 2); // Precio con 2 decimales
            $table->integer('mileage'); // Kilometraje
            $table->string('color')->nullable();
            $table->string('body_type')->nullable(); // Tipo de carrocería (Sedan, SUV, etc.)
            $table->string('transmission')->nullable(); // Transmisión (automática, manual)
            $table->string('engine_type')->nullable(); // Tipo de motor (gasolina, eléctrico, híbrido)
            $table->text('description')->nullable();
            $table->string('location')->nullable(); // Ubicación del vehículo
            $table->boolean('is_published')->default(false); // Si está publicado o no
            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};


// --- ARCHIVO: database/migrations/YYYY_MM_DD_HHMMSS_create_vehicle_images_table.php ---
// (El nombre del archivo tendrá una marca de tiempo generada automáticamente)

namespace Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vehicle_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade'); // Clave foránea a vehicles
            $table->string('image_path'); // Ruta relativa de la imagen en el servidor
            $table->boolean('is_thumbnail')->default(false); // Para indicar la imagen principal
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_images');
    }
};


// --- ARCHIVO: database/migrations/YYYY_MM_DD_HHMMSS_create_customization_settings_table.php ---
// (El nombre del archivo tendrá una marca de tiempo generada automáticamente)

namespace Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customization_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // Ej: 'site_name', 'logo_url', 'primary_color'
            $table->text('value');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customization_settings');
    }
};


// --- ARCHIVO: app/Models/Vehicle.php ---

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    // Campos que pueden ser asignados masivamente
    protected $fillable = [
        'brand', 'model', 'year', 'price', 'mileage', 'color', 'body_type',
        'transmission', 'engine_type', 'description', 'location', 'is_published'
    ];

    /**
     * Relación uno a muchos con VehicleImage.
     * Un vehículo puede tener muchas imágenes.
     */
    public function images()
    {
        return $this->hasMany(VehicleImage::class);
    }
}


// --- ARCHIVO: app/Models/VehicleImage.php ---

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleImage extends Model
{
    use HasFactory;

    // Nombre de la tabla si no sigue la convención de pluralización de Laravel
    protected $table = 'vehicle_images';

    // Campos que pueden ser asignados masivamente
    protected $fillable = [
        'vehicle_id', 'image_path', 'is_thumbnail'
    ];

    /**
     * Relación uno a muchos (inversa) con Vehicle.
     * Una imagen pertenece a un vehículo.
     */
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}


// --- ARCHIVO: app/Models/CustomizationSetting.php ---

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomizationSetting extends Model
{
    use HasFactory;

    // Nombre de la tabla si no sigue la convención de pluralización de Laravel
    protected $table = 'customization_settings';

    // Campos que pueden ser asignados masivamente
    protected $fillable = [
        'key', 'value'
    ];
}


// --- ARCHIVO: app/Http/Controllers/Api/VehicleController.php ---

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\VehicleImage; // Necesario para gestionar imágenes
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Para gestionar archivos
use Illuminate\Support\Facades\Validator; // Para validación

class VehicleController extends Controller
{
    /**
     * Muestra una lista de todos los vehículos publicados.
     * Permite filtrado y ordenamiento.
     */
    public function index(Request $request)
    {
        $query = Vehicle::query()->where('is_published', true);

        // --- Aplicar filtros ---
        if ($request->has('brand')) {
            $query->where('brand', $request->input('brand'));
        }
        if ($request->has('model')) {
            $query->where('model', 'like', '%' . $request->input('model') . '%');
        }
        if ($request->has('year_min')) {
            $query->where('year', '>=', $request->input('year_min'));
        }
        if ($request->has('year_max')) {
            $query->where('year', '<=', $request->input('year_max'));
        }
        if ($request->has('price_min')) {
            $query->where('price', '>=', $request->input('price_min'));
        }
        if ($request->has('price_max')) {
            $query->where('price', '<=', $request->input('price_max'));
        }
        if ($request->has('body_type')) {
            $query->where('body_type', 'like', '%' . $request->input('body_type') . '%');
        }
        if ($request->has('transmission')) {
            $query->where('transmission', $request->input('transmission'));
        }
        if ($request->has('engine_type')) {
            $query->where('engine_type', $request->input('engine_type'));
        }
        if ($request->has('location')) {
            $query->where('location', 'like', '%' . $request->input('location') . '%');
        }

        // --- Aplicar ordenamiento ---
        if ($request->has('sort_by')) {
            switch ($request->input('sort_by')) {
                case 'price-asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price-desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'year-desc':
                    $query->orderBy('year', 'desc');
                    break;
                case 'mileage-asc':
                    $query->orderBy('mileage', 'asc');
                    break;
                default:
                    // Por defecto o 'relevance'
                    $query->orderBy('created_at', 'desc'); // Últimos agregados
                    break;
            }
        } else {
            $query->orderBy('created_at', 'desc'); // Orden por defecto si no se especifica
        }

        // Paginar los resultados
        $vehicles = $query->with('images')->paginate(10); // Carga las imágenes relacionadas y pagina

        return response()->json($vehicles);
    }

    /**
     * Muestra los detalles de un vehículo específico.
     */
    public function show(Vehicle $vehicle)
    {
        // Solo mostrar si está publicado, o si es una solicitud desde el admin panel (con autenticación)
        if (!$vehicle->is_published && !auth()->check()) { // Ejemplo simple, la lógica real de auth iría aquí
            return response()->json(['message' => 'Vehicle not found or not published.'], 404);
        }
        return response()->json($vehicle->load('images')); // Carga las imágenes del vehículo
    }

    /**
     * Almacena un nuevo vehículo y sus imágenes.
     * Requiere autenticación (solo para administradores).
     */
    public function store(Request $request)
    {
        // Validación de datos
        $validator = Validator::make($request->all(), [
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'price' => 'required|numeric|min:0',
            'mileage' => 'required|integer|min:0',
            'color' => 'nullable|string|max:255',
            'body_type' => 'nullable|string|max:255',
            'transmission' => 'nullable|string|max:255',
            'engine_type' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'is_published' => 'boolean',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validación para imágenes
            'thumbnail_index' => 'nullable|integer', // Para seleccionar la imagen principal
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Crear el vehículo
        $vehicle = Vehicle::create($request->except(['images', 'thumbnail_index'])); // Excluir campos de imagen

        // Guardar imágenes
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $imageFile) {
                // Almacenar en el disco 'public' dentro de un subdirectorio 'vehicles'
                // La ruta real en el servidor será: storage/app/public/vehicles/{nombre_archivo.jpg}
                // Para que sea accesible públicamente, debes ejecutar 'php artisan storage:link'
                $path = $imageFile->store('media/vehicles', 'public'); // Almacena en storage/app/public/media/vehicles

                $isThumbnail = ($request->input('thumbnail_index') == $index);

                VehicleImage::create([
                    'vehicle_id' => $vehicle->id,
                    'image_path' => $path, // Guardamos la ruta relativa
                    'is_thumbnail' => $isThumbnail,
                ]);
            }
        }

        return response()->json($vehicle->load('images'), 201);
    }

    /**
     * Actualiza un vehículo existente y sus imágenes.
     * Requiere autenticación (solo para administradores).
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        $validator = Validator::make($request->all(), [
            'brand' => 'sometimes|required|string|max:255',
            'model' => 'sometimes|required|string|max:255',
            'year' => 'sometimes|required|integer|min:1900|max:' . (date('Y') + 1),
            'price' => 'sometimes|required|numeric|min:0',
            'mileage' => 'sometimes|required|integer|min:0',
            'color' => 'nullable|string|max:255',
            'body_type' => 'nullable|string|max:255',
            'transmission' => 'nullable|string|max:255',
            'engine_type' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'is_published' => 'boolean',
            'new_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Para nuevas imágenes
            'deleted_image_ids' => 'nullable|array', // IDs de imágenes a eliminar
            'deleted_image_ids.*' => 'integer',
            'thumbnail_id' => 'nullable|integer', // ID de la nueva imagen principal
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $vehicle->update($request->except(['new_images', 'deleted_image_ids', 'thumbnail_id']));

        // Eliminar imágenes existentes
        if ($request->has('deleted_image_ids')) {
            $imageIdsToDelete = $request->input('deleted_image_ids');
            $imagesToDelete = VehicleImage::whereIn('id', $imageIdsToDelete)->where('vehicle_id', $vehicle->id)->get();

            foreach ($imagesToDelete as $image) {
                Storage::disk('public')->delete($image->image_path); // Eliminar del almacenamiento
                $image->delete(); // Eliminar de la base de datos
            }
        }

        // Subir nuevas imágenes
        if ($request->hasFile('new_images')) {
            foreach ($request->file('new_images') as $imageFile) {
                $path = $imageFile->store('media/vehicles', 'public');
                VehicleImage::create([
                    'vehicle_id' => $vehicle->id,
                    'image_path' => $path,
                    'is_thumbnail' => false, // Por defecto no es thumbnail, se actualizará después
                ]);
            }
        }

        // Actualizar la imagen principal (thumbnail)
        if ($request->has('thumbnail_id')) {
            $vehicle->images()->update(['is_thumbnail' => false]); // Desactivar todas las existentes
            $newThumbnail = VehicleImage::find($request->input('thumbnail_id'));
            if ($newThumbnail && $newThumbnail->vehicle_id === $vehicle->id) {
                $newThumbnail->update(['is_thumbnail' => true]);
            }
        }

        return response()->json($vehicle->load('images'));
    }

    /**
     * Elimina un vehículo.
     * Requiere autenticación (solo para administradores).
     */
    public function destroy(Vehicle $vehicle)
    {
        // Eliminar todas las imágenes asociadas del almacenamiento
        foreach ($vehicle->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $vehicle->delete();
        return response()->json(null, 204); // No Content
    }
}


// --- ARCHIVO: app/Http/Controllers/Api/CustomizationController.php ---

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CustomizationSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; // Para validación
use Illuminate\Support\Facades\Storage; // Para subir logo

class CustomizationController extends Controller
{
    /**
     * Muestra todos los ajustes de personalización.
     * Es un endpoint público para que el frontend lo consuma.
     */
    public function index()
    {
        $settings = CustomizationSetting::all()->pluck('value', 'key'); // Transforma a un array asociativo (key => value)
        return response()->json($settings);
    }

    /**
     * Actualiza un ajuste de personalización específico o crea uno si no existe.
     * Requiere autenticación (solo para administradores).
     */
    public function update(Request $request)
    {
        // Validación para la clave y el valor
        $validator = Validator::make($request->all(), [
            'key' => 'required|string|max:255',
            'value' => 'required|string', // Se puede relajar si se espera un archivo, ver abajo
        ]);

        // Si la clave es 'logo_url', la validación para 'value' podría ser diferente
        if ($request->input('key') === 'logo_url' && $request->hasFile('value')) {
             $validator = Validator::make($request->all(), [
                'key' => 'required|string|max:255',
                'value' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validar como imagen
            ]);
        }

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $key = $request->input('key');
        $value = $request->input('value');

        // Lógica específica para el logo si se sube un archivo
        if ($key === 'logo_url' && $request->hasFile('value')) {
            // Eliminar logo anterior si existe para evitar archivos huérfanos
            $oldLogo = CustomizationSetting::where('key', 'logo_url')->first();
            if ($oldLogo && Storage::disk('public')->exists($oldLogo->value)) {
                Storage::disk('public')->delete($oldLogo->value);
            }
            // Almacenar el nuevo logo
            $value = $request->file('value')->store('media/site', 'public'); // Almacena en storage/app/public/media/site
        }

        $setting = CustomizationSetting::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        return response()->json($setting);
    }

    /**
     * Inicializa los ajustes de personalización por defecto si no existen.
     * Solo debería ejecutarse una vez (ej: con un seeder o la primera vez que se inicia el admin).
     */
    public function initializeDefaultSettings()
    {
        $defaultSettings = [
            'site_name' => 'AutoMarket Latino',
            'logo_url' => 'https://placehold.co/60x60/3b82f6/ffffff?text=LOGO', // URL placeholder
            'primary_color' => '#2563eb', // blue-600 de Tailwind
            'secondary_color' => '#3b82f6', // blue-500 de Tailwind
            'footer_text' => '© 2023 AutoMarket Latino. Todos los derechos reservados.',
        ];

        foreach ($defaultSettings as $key => $value) {
            CustomizationSetting::firstOrCreate(['key' => $key], ['value' => $value]);
        }

        return response()->json(['message' => 'Default settings initialized or already exist.']);
    }
}

// --- ARCHIVO: routes/api.php ---
// (Modifica este archivo para definir tus rutas API)

namespace App\Http\Controllers\Api; // Agrega esto al inicio si no está

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Rutas API públicas para el frontend
Route::get('settings', [CustomizationController::class, 'index']); // Obtener configuración
Route::get('vehicles', [VehicleController::class, 'index']);      // Listar vehículos
Route::get('vehicles/{vehicle}', [VehicleController::class, 'show']); // Detalle del vehículo

// Rutas API para el panel de administración (protegidas por autenticación)
// Aquí se debería añadir un middleware de autenticación (ej: auth:api o Sanctum)
Route::middleware('auth:api')->group(function () { // Se asume que configurarás Laravel Sanctum/Passport
    Route::post('vehicles', [VehicleController::class, 'store']);
    Route::put('vehicles/{vehicle}', [VehicleController::class, 'update']);
    Route::delete('vehicles/{vehicle}', [VehicleController::class, 'destroy']);
    Route::post('settings', [CustomizationController::class, 'update']); // Actualizar setting
    Route::post('settings/initialize', [CustomizationController::class, 'initializeDefaultSettings']); // Inicializar settings
});

// Nota: Puedes agregar una ruta para el login del admin aquí mismo o en routes/web.php si usas Blade.
// Ejemplo simple de ruta de autenticación (requiere Laravel Breeze/Fortify configurado)
// Route::post('/login', [AuthenticatedSessionController::class, 'store'])->middleware('guest');


// --- ARCHIVO: config/cors.php ---
// Modifica este archivo para configurar las cabeceras CORS.
// Busca la sección 'paths' y 'allowed_origins'.

/*
return [

    'paths' => ['api/*', 'sanctum/csrf-cookie', 'login', 'logout'], // Añade o asegura que 'api/*' esté aquí

    'allowed_methods' => ['*'],

    // Aquí es donde defines los dominios que pueden acceder a tu API.
    // Asegúrate de que el dominio de tu frontend esté aquí.
    'allowed_origins' => ['http://localhost:8000', 'https://automarket.socialmarketinglatino.site'], // Agrega tu dominio del frontend

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,

];
*/

// --- ARCHIVO: app/Http/Kernel.php ---
// Asegúrate de que el middleware de CORS esté habilitado.
// Busca la sección `$middlewareGroups` y dentro de 'api', asegúrate de que exista:
// \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
// \App\Http\Middleware\TrustProxies::class, // Si usas proxies
// \Fruitcake\Cors\HandleCors::class, // Este es el middleware de CORS
// \Illuminate\Routing\Middleware\SubstituteBindings::class,


/*
protected $middlewareGroups = [
    'web' => [
        // ... otros middlewares
        \App\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        // \Illuminate\Session\Middleware\AuthenticateSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\VerifyCsrfToken::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
    ],

    'api' => [
        // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class, // Descomentar si usas Sanctum para SPAs
        \Illuminate\Routing\Middleware\ThrottleRequests::class.':api',
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
        \Fruitcake\Cors\HandleCors::class, // <--- Asegúrate de que esta línea esté aquí
    ],
];
*/

// --- Notas Adicionales sobre el manejo de imágenes ---
// La ruta de almacenamiento configurada es 'public' disk de Laravel.
// Esto guarda las imágenes en 'storage/app/public/media/vehicles' o 'storage/app/public/media/site'.
// Para que estas imágenes sean accesibles públicamente desde el navegador a través de tu dominio,
// necesitas crear un "symlink" (enlace simbólico) desde `public/storage` a `storage/app/public`.
// Esto se hace con el comando: `php artisan storage:link`
// La URL para acceder a las imágenes será entonces:
// `https://automarket.socialmarketinglatino.site/storage/media/vehicles/nombre_archivo.jpg`
// `https://automarket.socialmarketinglatino.site/storage/media/site/logo.png`
//
// NOTA IMPORTANTE: La ruta que especificaste en tu solicitud fue `/www/wwwroot/automarket.socialmarketinglatino.site/media`.
// Laravel gestiona el almacenamiento de archivos de forma predeterminada dentro de `storage/app/public` (accesible vía `public/storage`).
// Si *necesitas* que los archivos estén directamente en `/www/wwwroot/automarket.socialmarketinglatino.site/media`
// sin el `public/storage` intermedio, esto requeriría una configuración de disco personalizada en
// `config/filesystems.php` apuntando a esa ruta absoluta.
// Sin embargo, la forma estándar y recomendada con Laravel es usar el symlink `php artisan storage:link`,
// lo que resulta en URLs como `.../storage/media/vehicles/...`.
// Por simplicidad y buenas prácticas de Laravel, he asumido el uso del sistema de almacenamiento 'public' y el symlink.
// Si tu servidor tiene una configuración muy específica que requiera la ruta exacta `/www/wwwroot/automarket.socialmarketinglatino.site/media`
// para las imágenes, por favor házmelo saber para ajustar `config/filesystems.php` y las rutas de almacenamiento.
