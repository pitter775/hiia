@extends('layouts.app', [
    'elementActive' => 'instagram'
])
@section('content')

@if(session('success'))
    <p style="color: green;">{{ session('success') }}</p>
@endif
<style>
    #cropContainer {
        width: 300px;
        height: 300px;
        margin: 20px auto;
        overflow: hidden;
        border: 1px solid #ccc;
        display: flex;
        justify-content: center;
        align-items: center;
    }
</style>




<div class="">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
 
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h3 class="float-left mb-0">Atendente para Instagram</h3>
                
                        </div>
                    </div>
                </div>

            </div>
            <div class="content-body">
                <div class="row">
                    <!-- Basic Tables start -->
                    <div class="col-lg-8 col-12 order-1 order-lg-2">

                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Vincular Conta do Instagram</h4>
                        </div>
                        <div class="card-body">
                            <div id="instagram-vincular-alerta"></div>
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="mb-1">
                                            <label class="form-label">Modelo de Atendente</label>
                                            <select id="modelo_id" class="form-control" required>
                                                <option value="">Selecione um modelo</option>
                                                @foreach ($modelos as $modelo)
                                                <option value="{{ $modelo->id }}">{{ $modelo->nome }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="mb-1">
                                            <label class="form-label">ID da Conta Comercial (Instagram Business)</label>
                                            <input type="text" id="ig_business_id" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                            <div class="mb-1">
                                                <label class="form-label">Token de Acesso</label>
                                                <input type="text" id="token" class="form-control" required>
                                            </div>
                                    </div>
                                    <div class="col-md-6">
                                            <div class="mb-1">
                                                <label class="form-label">Nome da Conta (opcional)</label>
                                                <input type="text" id="nome_conta" class="form-control">
                                            </div>
                                    </div>
                                    <div class="col-md-12">
                                                <button id="btnVincularInstagram" class="btn btn-primary">Vincular Conta</button>
                                    </div> 
                                </div>
                            </div>
                        </div>



                        <!-- post 1 -->
                        <div class="card">
                            <div class="card-body">
                                <form id="formAtendente">
                                    <div class="d-flex justify-content-start align-items-center mb-1">
                                    
                                        <!--/ avatar -->
                                        <div class="profile-user-info">
                                            <h5 class="mb-0">Configurando Atendente</h5>
                                            <small class="text-muted">Todos os dados inseridos são privados.</small>
                                        </div>
                                    </div>
                                    <p class="card-text"> Utilize esta página para criar o modelo de atendimento ideal para o seu site. Preencha o campo, 
                                        configure os detalhes do treinamento e personalize o conteúdo conforme as necessidades do seu negócio.
                                        Esse modelo será a base para orientar o atendimento e melhorar a experiência dos usuários.</p>

                                    <hr>
                                    <div class="row">
                                    
                                        <div style="text-align: center; margin: 20px;">
                                                <label for="imagemAtendente" style="cursor: pointer;">
                                                    <img 
                                                        id="imagemAvatar" 
                                                        src="{{ asset('assets/img/semfoto.jpg') }}" 
                                                        alt="Avatar" 
                                                        style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover;"
                                                    />
                                                </label>
                                            </div>
                                            <input type="file" id="imagemAtendente" accept="image/*" style="display: none;" />
                                            <input type="hidden" id="imagemFinal" />
                                        
                                            <div class="form-group mt-2 col-lg-5 col-12 order-4 ">
                                                <label for="nome">Nome do Modelo de Atendimento</label>
                                                <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome">
                                            </div>
                                        </div>
                            
                                    <!-- comment box -->
                                    <label for="descricao ">Descrição</label>
                                    <fieldset class="form-label-group mb-75">
                                    
                                        <textarea class="form-control " id="descricao" rows="3" style="height: 300px" name="descricao" placeholder='Exemplo de instrução do Atendente:
- Você é um atendente da empresa SoluçõesX.
- Nosso foco é fornecer suporte técnico e comercial para sistemas financeiros e assinaturas de serviços digitais.
- Suas respostas devem ser claras, educadas e sempre baseadas nas informações disponíveis no sistema.
- Sempre solicite o nome completo e o número de CPF do cliente antes de iniciar o atendimento.
- Caso o cliente esteja interessado em adquirir o sistema:
- "Você já tem uma data prevista para iniciar a implementação do projeto?"
- "O sistema será utilizado por quantos usuários simultaneamente?"
- Planos disponíveis:
- Assinatura mensal: R$ 99,90
- Assinatura anual (com 15% de desconto): R$ 1.019,10
- Taxa de cancelamento antes de 6 meses: R$ 49,90
- A assinatura tem validade até a data especificada no contrato e pode ser renovada automaticamente.
- Suporte disponível de segunda a sexta, das 8h às 18h.
- Termine o atendimento sempre com: "Posso te ajudar em algo mais?"
'></textarea>
                                    
                                    </fieldset>
                                    <!--/ comment box -->
                                    <button type="button" id="salvaRascunho" class="btn btn-sm btn-primary">Salvar Rascunho</button>
                                    
                                </form>
                            </div>
                        </div>



                    </div>



                    <!-- Basic Tables end -->

                     <!-- right profile info section -->
                    <div class="col-lg-4 col-12 order-4">
                        <!-- latest profile pictures -->
                        <style>
                            #nomeModelo{ float: right}
                            #estadoModelo{ float: right}
                            #dataRascunho{ float: right}
                            #dataAtivo{ float: right}
                        </style>
                        <div class="card">
                            <div class="card-body">                         
                                <div class="profile-user-info mb-2">
                                    <h5 class="mb-0">Status do Atendente</h5>
                                    <small class="text-muted">Define o uso e o estado do Atendente.</small>
                                </div>

                                <p class="mb-0"> <strong> Modelo Criado:</strong>  <span id="nomeModelo"> __</span> </p>
                                <p class="mb-0"> <strong> Estado:</strong>  <span id="estadoModelo"> __</span> </p>
                                <p class="mb-0"> <strong> Data Rascunho:</strong>  <span id="dataRascunho"> __</span> </p>
                                <p class="mb-0"> <strong> Data Atualizado:</strong>  <span id="dataAtivo"> __</span> </p>

                                <hr>
                                
                                <button type="button" id="ativarAtendente" class="btn btn-sm btn-primary mr-1">Ativar Atendente</button>
                   
                               
                            </div>
                        </div>




                        <!-- latest profile pictures -->
                        <div class="card">
                            <div class="card-body">
                         
                                <h5 class="mb-1">Por que funciona bem?</h5>
                             
                                    <p class="card-text" style="color: #666">                        
                                    1. <strong>Abrangente:</strong> Cobre aspectos importantes como comportamento, processos e escopo do atendimento.<br><br>
                                    2. <strong>Intuitivo:</strong> Fornece instruções claras sobre como o atendente deve proceder em situações comuns.<br><br>
                                    3. <strong>Personalizável:</strong> O administrador pode ajustar facilmente os detalhes (ex: horário de atendimento ou processos internos) para se adequar à realidade do negócio. </p>

                                    <hr>

                                    <p class="card-text" style="color: #000">   
                                    
                                    Personalize as informações do treinamento conforme as necessidades da sua equipe ou negócio.
                                    </p>
                              
                            
                            </div>
                        </div>


                    </div>
                  
                </div>
            </div>
        </div>
    </div>


@endsection

@push('css_vendor')

    <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/forms/select/select2.min.css">   
    <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/pickers/pickadate/pickadate.css">  
    <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css">  
    <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/extensions/toastr.min.css">

@endpush

@push('css_page')
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/pages/app-user.css">
    
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/plugins/extensions/ext-component-sweet-alerts.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/plugins/forms/pickers/form-flat-pickr.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/plugins/forms/pickers/form-pickadate.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/plugins/forms/form-validation.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/plugins/extensions/ext-component-toastr.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-solarizedlight.min.css" rel="stylesheet">
    
    <!-- Dropzone CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css" />

    <!-- Cropper.js CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />



@endpush

@push('js_page')
    <script src="../../../app-assets/js/scripts/forms/form-select2.js"></script> 
    <script src="../../../app-assets/js/scripts/extensions/ext-component-sweet-alerts.js"></script>
    <script src="../../../app-assets/js/scripts/forms/pickers/form-pickers.js"></script>
    <script src="../../../app-assets/js/scripts/pages/app-chat-intagram.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-javascript.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-html.min.js"></script> 
    <!-- Dropzone.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>

    <!-- Cropper.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>


@endpush

@push('js_vendor')
    <script src="../../../app-assets/vendors/js/forms/select/select2.full.min.js"></script>    
    
    <script src="../../../app-assets/vendors/js/extensions/polyfill.min.js"></script>
    <script src="../../../app-assets/vendors/js/pickers/pickadate/picker.js"></script>
    <script src="../../../app-assets/vendors/js/pickers/pickadate/picker.date.js"></script>
    <script src="../../../app-assets/vendors/js/pickers/pickadate/picker.time.js"></script>
    <script src="../../../app-assets/vendors/js/pickers/pickadate/legacy.js"></script>
    <script src="../../../app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js"></script>


    
@endpush







