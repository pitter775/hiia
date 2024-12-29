@extends('layouts.app', [
    'elementActive' => 'modelos'
])
@section('content')

@if(session('success'))
    <p style="color: green;">{{ session('success') }}</p>
@endif


<div class="">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
 
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h3 class="float-left mb-0">Gerenciar Atendente</h3>
                
                        </div>
                    </div>
                </div>

            </div>
            <div class="content-body">
                <div class="row">
                    <!-- Basic Tables start -->
                    <div class="col-lg-8 col-12 order-1 order-lg-2">
                        <!-- post 1 -->
                        <div class="card">
                            <div class="card-body">
                                <form id="formAtendente">
                                    <div class="d-flex justify-content-start align-items-center mb-1">
                                    
                                        <!--/ avatar -->
                                        <div class="profile-user-info">
                                            <h5 class="mb-0">Criando Atendente</h5>
                                            <small class="text-muted">Todos os dados inseridos são privados.</small>
                                        </div>
                                    </div>
                                    <p class="card-text"> Utilize esta página para criar o modelo de atendimento ideal para o seu site. Preencha o campo, 
                                        configure os detalhes do treinamento e personalize o conteúdo conforme as necessidades do seu negócio.
                                        Esse modelo será a base para orientar o atendimento e melhorar a experiência dos usuários.</p>

                                    <p>Cada modelo pode conter até <strong>1.000 palavras</strong>. 
                                        Recomendamos ser claro e objetivo para que o atendimento seja eficaz. 
                                        Caso precise incluir mais informações, ajuste o conteúdo para manter-se dentro do limite.
                                    </p>
                                    <div class="row">
                                        <div class="form-group mb-2 col-lg-5 col-12 order-4 ">
                                            <label for="nome">Nome do Modelo de Atendimento</label>
                                            <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome">
                                        </div>
                                    </div>
                                    <!-- comment box -->
                                    <label for="descricao ">Descrição</label>
                                    <fieldset class="form-label-group mb-75">
                                    
                                        <textarea class="form-control " id="descricao" rows="3" style="height: 400px" name="descricao" placeholder='Exemplo de instrução do Atendente:
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
                                <p class="mb-0"> <strong> Data Ativo:</strong>  <span id="dataAtivo"> __</span> </p>

                                <hr>
                                
                                <button type="button" id="ativarAtendente" class="btn btn-sm btn-primary mr-1">Ativar Atendente</button>
                   
                               
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">                         
                                <div class="profile-user-info mb-2">
                                    <h5 class="mb-0">Permissões</h5>
                                    <small class="text-muted">Define quias dominios podem usar o chat.</small>
                                </div>

                                <div class="form-group">
                                    <label for="dominio">Adicionar Domínio</label>
                                    <input type="text" id="dominio" class="form-control" placeholder="Exemplo: meusite.com" />
                                    <button type="button" id="addDomain" class="btn btn-sm btn-success mt-2">Adicionar</button>
                                </div>

                                <ul id="domainList" class="list-group mt-3"></ul>
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
    <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/tables/datatable/responsive.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/tables/datatable/buttons.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/tables/datatable/rowGroup.bootstrap4.min.css">
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
@endpush

@push('js_page')
    <script src="../../../app-assets/vendors/js/forms/validation/jquery.validate.min.js"></script>
    
    <script src="../../../app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js"></script>
    <script src="../../../app-assets/vendors/js/tables/datatable/datatables.bootstrap4.min.js"></script>
    <script src="../../../app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js"></script>
    <script src="../../../app-assets/vendors/js/tables/datatable/responsive.bootstrap4.js"></script>
    <script src="../../../app-assets/vendors/js/tables/datatable/datatables.checkboxes.min.js"></script> 
    <script src="../../../app-assets/vendors/js/tables/datatable/datatables.buttons.min.js"></script>
    <script src="../../../app-assets/vendors/js/tables/datatable/jszip.min.js"></script>
    <script src="../../../app-assets/vendors/js/tables/datatable/vfs_fonts.js"></script>
    <script src="../../../app-assets/vendors/js/tables/datatable/buttons.html5.min.js"></script>
    <script src="../../../app-assets/vendors/js/tables/datatable/buttons.print.min.js"></script>
    <script src="../../../app-assets/vendors/js/tables/datatable/dataTables.rowGroup.min.js"></script>
    
    
    <script src="../../../app-assets/js/scripts/forms/form-select2.js"></script> 
    <script src="../../../app-assets/js/scripts/extensions/ext-component-sweet-alerts.js"></script>
    <script src="../../../app-assets/js/scripts/forms/pickers/form-pickers.js"></script>

    <script src="../../../app-assets/js/scripts/pages/app-modelo-chat.js"></script>
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



