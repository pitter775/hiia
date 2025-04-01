-- Estrutura inicial para o banco de dados do projeto de chat personalizado
<?
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->timestamp('email_verified_at')->nullable();
    $table->string('password');
    $table->string('tipo_usuario')->default('cliente'); // cliente ou admin           
    $table->string('photo')->nullable(); // URL da foto do usuário
    $table->string('telefone')->nullable(); // Telefone com DDD          
    $table->string('status')->default('ativo'); // status: 'ativo' ou 'inativo'
    $table->rememberToken();
    $table->timestamps();
});

Schema::create('chats', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('user_id');
    $table->unsignedBigInteger('modelo_id');
    $table->text('mensagem_usuario')->nullable(); // Mensagem enviada pelo usuário
    $table->text('resposta_gpt')->nullable(); // Resposta gerada pelo GPT
    $table->timestamps();

    // Relacionamentos
    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    $table->foreign('modelo_id')->references('id')->on('modelos')->onDelete('cascade');
});

Schema::create('mensagens', function (Blueprint $table) {
    $table->id();
    $table->foreignId('chat_id')->constrained('chats')->onDelete('cascade');
    $table->text('conteudo');
    $table->enum('remetente', ['cliente', 'gpt']); // Diferencia quem enviou a mensagem
    $table->timestamp('enviado_em')->useCurrent();
});

Schema::create('modelos', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('user_id');
    $table->string('nome');
    $table->text('descricao')->nullable();
    $table->text('allowed_domains')->nullable(); // Domínios permitidos para uso
    $table->json('dados')->nullable(); // Dados otimizados para personalização e performance
    $table->timestamps();

    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
});

Schema::create('chat_usuarios', function (Blueprint $table) {
    $table->id();
    $table->foreignId('chat_id')->constrained('chats')->onDelete('cascade');
    $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
    $table->timestamp('criado_em')->useCurrent();
});

-- Relacionamentos no Laravel

-- Modelo: Chat
use HasFactory;

// Permite preenchimento em massa nos seguintes campos
protected $fillable = [
    'user_id',        // Relacionamento com o usuário
    'modelo_id',      // Relacionamento com o modelo
    'mensagem_usuario', // Mensagem enviada pelo usuário
    'resposta_gpt',   // Resposta gerada pelo GPT
];

// Relacionamento com o usuário
public function user() {
    return $this->belongsTo(User::class);
}

// Relacionamento com o modelo
public function modelo() {
    return $this->belongsTo(Modelo::class);
}

// Relacionamento com mensagens
public function mensagens() {
    return $this->hasMany(Mensagem::class);
}

-- Modelo: Mensagem
use HasFactory;

// Permite preenchimento em massa nos seguintes campos
protected $fillable = [
    'chat_id',       // Relacionamento com o chat
    'usuario_id',    // Relacionamento com o usuário
    'conteudo',      // Conteúdo da mensagem
    'remetente',     // Quem enviou (cliente ou GPT)
    'enviado_em',    // Data e hora do envio
];

// Relacionamento com o chat
public function chat() {
    return $this->belongsTo(Chat::class);
}

// Relacionamento com o usuário
public function usuario() {
    return $this->belongsTo(User::class);
}

-- Modelo: Usuario
use HasFactory;

// Permite preenchimento em massa nos seguintes campos
protected $fillable = [
    'name',          // Nome do usuário
    'email',         // Email do usuário
    'password',      // Senha do usuário
    'tipo_usuario',  // Tipo do usuário (cliente ou admin)
    'photo',         // URL da foto do usuário
    'telefone',      // Telefone com DDD
    'status',        // Status do usuário
];

// Relacionamento com mensagens
public function mensagens() {
    return $this->hasMany(Mensagem::class);
}

// Relacionamento com chats
public function chats() {
    return $this->belongsToMany(Chat::class, 'chat_usuarios');
}

-- Modelo: Modelo
use HasFactory;

// Permite preenchimento em massa nos seguintes campos
protected $fillable = [
    'user_id',   // Relacionamento com o usuário
    'nome',      // Nome do modelo
    'descricao', // Descrição opcional
    'dados',     // Informações do modelo em JSON ou texto
    'allowed_domains', // Domínios permitidos para uso
];

// Relacionamento com o usuário
public function user() {
    return $this->belongsTo(User::class);
}

// Relacionamento com chats
public function chats() {
    return $this->hasMany(Chat::class);
}
