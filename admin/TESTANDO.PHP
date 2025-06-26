TRANSFORMA ESSE FORMULARIO EM ETAPAS:    <div class="sb2-2-add-blog sb2-2-1">
                        <h2>Adicionar Curso</h2>
                        <ul class="nav nav-tabs tab-list">
                            <li class="active"><a data-toggle="tab" href="#home" aria-expanded="true"><i class="fa fa-info" aria-hidden="true"></i> <span>Detalhe</span></a>
                            </li>
                            <li class=""><a data-toggle="tab" href="#menu1" aria-expanded="false"><i class="fa fa-bed" aria-hidden="true"></i> <span>Programa do curso</span></a>
                            </li>
                            <li class=""><a data-toggle="tab" href="#menu2" aria-expanded="false"><i class="fa fa-picture-o" aria-hidden="true"></i> <span>Imagem do banner</span></a>
                            </li>
                            <li class=""><a data-toggle="tab" href="#menu3" aria-expanded="false"><i class="fa fa-facebook" aria-hidden="true"></i> <span>Horário</span></a>
                            </li>
                            <!--
                        <li class=""><a data-toggle="tab" href="#menu4" aria-expanded="false"><i class="fa fa-phone" aria-hidden="true"></i> <span>Informações de contato</span></a>
                        </li>
                        !-->
                        </ul>




                        <form method="post" enctype="multipart/form-data" class="tab-content">



                            <div id="home" class="tab-pane fade active in">
                                <div class="box-inn-sp">
                                    <div class="inn-title">
                                        <h4>Informações do curso</h4>
                                    </div>
                                    <div class="bor">

                                        <div class="row">
                                            <div class="input-field col s12">
                                                <input id="nome" name="nome" type="text" class="validate">
                                                <label for="nome" class="">Nome do curso</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input-field col s12">
                                                <textarea name="descricao" class="materialize-textarea"></textarea>
                                                <label>Descrições do curso:</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input-field col s12">
                                                <input id="faculdade" name="faculdade" type="text" class="validate">
                                                <label for="faculdade" class="">Faculdade</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <label for="inicio" class="">Data de Inicio</label>
                                            <div class="input-field col s12">
                                                <input id="inicio" name="inicio" type="date" class="validate">

                                            </div>
                                        </div>
                                        <div class="row">
                                            <label for="fim" class="">Data de Termino</label>
                                            <div class="input-field col s12">
                                                <input id="fim" name="fim" type="date" class="validate">
                                            </div>
                                        </div>
                                        <div class="row">

                                            <div class="input-field col s12">
                                                <input id="duracao" name="duracao" type="text" class="validate">
                                                <label for="duracao" class="">Duração</label>
                                            </div>
                                        </div>
                                        <div class="row">

                                            <div class="input-field col s12">
                                                <input id="total_vaga" name="total_vaga" type="number" class="validate">
                                                <label for="total_vaga" class="">Total de Vaga</label>

                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="input-field col s12">
                                                <select name="estado">
                                                    <option value="" disabled selected>Selecione o status</option>
                                                    <option value="1">Ativo</option>
                                                    <option value="2">Desativado</option>
                                                    <option value="3">Excluir</option>
                                                </select>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </div>
                            <div id="menu1" class="tab-pane fade">
                                <div class="inn-title">
                                    <h4>Requisitos, taxas, perfil do aluno e como se inscrever</h4>
                                </div>
                                <div class="bor ad-cou-deta-h4">

                                    <h4>Requisitos:</h4>
                                    <div class="row">
                                        <div class="input-field col s12">
                                            <textarea name="descricao_requisitos" class="materialize-textarea"></textarea>
                                            <label>Descrições do curso:</label>
                                        </div>
                                    </div>
                                    <h4>Taxa:</h4>
                                    <div class="row">
                                        <div class="input-field col s12">
                                            <input type="number" name="taxa_1termo" class="validate" required>
                                            <label>1º termo honorários</label>
                                        </div>
                                        <div class="input-field col s12">
                                            <input type="number" name="taxa_2termo" class="validate" required>
                                            <label>Taxas de 2º termo</label>
                                        </div>
                                        <div class="input-field col s12">
                                            <input type="number" name="taxa_3termo" class="validate" required>
                                            <label>Taxas de 3º termo</label>
                                        </div>
                                        <div class="input-field col s12">
                                            <textarea name="descricao_precos" class="materialize-textarea"></textarea>
                                            <label>Descrições de preços:</label>
                                        </div>
                                    </div>
                                    <h4>Perfil do aluno:</h4>
                                    <div class="row">
                                        <div class="input-field col s12">
                                            <textarea name="descricao_do_perfil_aluno" class="materialize-textarea"></textarea>
                                            <label>Descrições do perfil do aluno:</label>
                                        </div>
                                    </div>
                                    <h4>Como aplicar este curso:</h4>
                                    <div class="row">
                                        <div class="input-field col s12">
                                            <input name="aplicacao_desc_etapa1" type="text" class="validate" required>
                                            <label>Descrições da Etapa 1:</label>
                                        </div>
                                        <div class="input-field col s12">
                                            <input name="aplicacao_desc_etapa2" type="text" class="validate" required>
                                            <label>Descrições da Etapa 2:</label>
                                        </div>
                                        <div class="input-field col s12">
                                            <input name="aplicacao_desc_etapa3" type="text" class="validate" required>
                                            <label>Descrições da Etapa 3:</label>
                                        </div>
                                        <div class="input-field col s12">
                                            <input name="aplicacao_desc_etapa4" type="text" class="validate" required>
                                            <label>Descrições da Etapa 4:</label>
                                        </div>
                                        <div class="input-field col s12">
                                            <input name="aplicacao_desc_etapa5" type="text" class="validate" required>
                                            <label>Descrições da Etapa 5:</label>
                                        </div>
                                    </div>


                                </div>
                            </div>
                            <div id="menu2" class="tab-pane fade">
                                <div class="inn-title">
                                    <h4>Galeria de fotos</h4>
                                    <p>Hotéis no aeroporto: a maneira certa de começar umas férias curtas</p>
                                </div>
                                <div class="bor">

                                    <div class="file-field input-field">
                                        <div class="btn admin-upload-btn">
                                            <span>Arquivo</span>
                                            <input name="img_banner" type="file" multiple="">
                                        </div>
                                        <div class="file-path-wrapper">
                                            <input class="file-path validate" type="text" placeholder="Carregar imagem do banner do curso">
                                        </div>
                                    </div>



                                </div>
                            </div>
                            <div id="menu3" class="tab-pane fade">
                                <div class="inn-title">
                                    <h4>Tabela de horários</h4>
                                </div>
                                <div class="bor ad-cou-deta-h4">

                                    <h4>1º semestre:</h4>
                                    <div class="row">
                                        <div class="input-field col s12">
                                            <input name="primeiro_semestre_titulo" type="text" class="validate" required>
                                            <label>Título:</label>
                                        </div>
                                        <div class="input-field col s12">
                                            <textarea name="primeiro_semestre_desc" class="materialize-textarea"></textarea>
                                            <label>Descrições:</label>
                                        </div>
                                    </div>
                                    <h4>2º semestre:</h4>
                                    <div class="row">
                                        <div class="input-field col s12">
                                            <input name="segundo_semestre_titulo" type="text" class="validate" required>
                                            <label>Título:</label>
                                        </div>
                                        <div class="input-field col s12">
                                            <textarea name="segundo_semestre_desc" class="materialize-textarea"></textarea>
                                            <label>Descrições:</label>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="input-field col s12">
                                            <i class="waves-effect waves-light btn-large waves-input-wrapper"><input type="submit" value="Cadastrar" name="cadastrar" class="waves-button-input"></i>
                                        </div>
                                    </div>



                                </div>
                            </div>


                    </div>
