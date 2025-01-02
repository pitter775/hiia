
-- Estrutura inicial para o banco de dados do projeto de chat personalizado
<?


use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

Schema::create('chats', function (Blueprint $table) {
    $table->id();
    $table->string('nome');
    $table->text('descricao')->nullable();
    $table->timestamps();
});

Schema::create('mensagens', function (Blueprint $table) {
    $table->id();
    $table->foreignId('chat_id')->constrained('chats')->onDelete('cascade');
    $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
    $table->text('conteudo');
    $table->timestamp('enviado_em')->useCurrent();
});

Schema::create('modelos', function (Blueprint $table) {
    $table->id();
    $table->string('nome');
    $table->text('descricao')->nullable();
    $table->timestamps();
});

Schema::create('chat_usuarios', function (Blueprint $table) {
    $table->id();
    $table->foreignId('chat_id')->constrained('chats')->onDelete('cascade');
    $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
    $table->timestamp('criado_em')->useCurrent();
});

Schema::create('failed_jobs', function (Blueprint $table) {
    $table->id();
    $table->string('connection');
    $table->string('queue');
    $table->longText('payload');
    $table->longText('exception');
    $table->timestamp('failed_at')->useCurrent();
});

Schema::create('migrations', function (Blueprint $table) {
    $table->id();
    $table->string('migration');
    $table->integer('batch');
});

Schema::create('password_reset_tokens', function (Blueprint $table) {
    $table->string('email')->primary();
    $table->string('token');
    $table->timestamp('created_at')->nullable();
});

Schema::create('personal_access_tokens', function (Blueprint $table) {
    $table->id();
    $table->morphs('tokenable');
    $table->string('name');
    $table->string('token', 64)->unique();
    $table->text('abilities')->nullable();
    $table->timestamp('last_used_at')->nullable();
    $table->timestamp('expires_at')->nullable();
    $table->timestamps();
});

// -- Comandos Artisan para criar as migrations correspondentes
// php artisan make:migration create_chats_table
// php artisan make:migration create_mensagens_table
// php artisan make:migration create_modelos_table
// php artisan make:migration create_chat_usuarios_table
// php artisan make:migration create_failed_jobs_table
// php artisan make:migration create_migrations_table
// php artisan make:migration create_password_reset_tokens_table
// php artisan make:migration create_personal_access_tokens_table

// -- Relacionamentos no Laravel

// -- Modelo: Chat
public function mensagens() {
    return $this->hasMany(Mensagem::class);
}

public function usuarios() {
    return $this->belongsToMany(Usuario::class, 'chat_usuarios');
}

-- Modelo: Mensagem
public function chat() {
    return $this->belongsTo(Chat::class);
}

public function usuario() {
    return $this->belongsTo(Usuario::class);
}

-- Modelo: Usuario
public function mensagens() {
    return $this->hasMany(Mensagem::class);
}

public function chats() {
    return $this->belongsToMany(Chat::class, 'chat_usuarios');
}
