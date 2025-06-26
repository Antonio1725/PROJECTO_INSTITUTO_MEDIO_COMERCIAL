<?php
include_once "header.php";
include_once "container_pricipal.php";
include_once "menulateral.php";

?>


            <!--== BODY INNER CONTAINER ==-->
            <div class="sb2-2">
                <!--== breadcrumbs ==-->
                <div class="sb2-2-2">
                    <ul>
                        <li><a href="index-2.html"><i class="fa fa-home" aria-hidden="true"></i> Home</a>
                        </li>
                        <li class="active-bre"><as href="#"> Adicionar Páginas</as>
                        </li>
                        <li class="page-back"><a href="index-2.html"><i class="fa fa-backward" aria-hidden="true"></i> Voltar</a>
                        </li>
                    </ul>
                </div>

                <!--== User Details ==-->
                <div class="sb2-2-3">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box-inn-sp admin-form">
<div class="sb2-2-add-blog sb2-2-1">
                    <h2>Adicionar nova Pagina</h2>
                    <p>A classe .table adiciona estilo básico (preenchimento leve e apenas divisores horizontais) a uma tabela:</p>

                    <ul class="nav nav-tabs tab-list">
                        <li class="active"><a data-toggle="tab" href="#home" aria-expanded="true"><i class="fa fa-info" aria-hidden="true"></i> <span>Detalhe da página</span></a>
                        </li>
                        <li class=""><a data-toggle="tab" href="#menu1" aria-expanded="false"><i class="fa fa-bed" aria-hidden="true"></i> <span>Corpo</span></a>
                        </li>
                        <li class=""><a data-toggle="tab" href="#menu2" aria-expanded="false"><i class="fa fa-picture-o" aria-hidden="true"></i> <span>Imagem do banner</span></a>
                        </li>
                        <li class=""><a data-toggle="tab" href="#menu3" aria-expanded="false"><i class="fa fa-facebook" aria-hidden="true"></i> <span>SEO</span></a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div id="home" class="tab-pane fade active in">
                            <div class="box-inn-sp">
                                <div class="inn-title">
                                <h4>Informações básicas da página</h4>
                                <p>Hotéis no aeroporto: a maneira certa de começar umas férias curtas</p>
                                </div>
                                <div class="bor">
                                    <form>
                                        <div class="row">
                                            <div class="input-field col s12">
                                                <input type="text" class="validate">
                                                <label class="">Título da página</label>
                                            </div>
                                            <div class="input-field col s12">
                                                <input type="text" class="validate">
                                                <label>URL da página</label>
                                            </div>
                                        </div>
										<div class="row">
											<div class="input-field col s12">
												<select>
                                                <option value="" disabled selected>Selecionar modelo de página</option>
                                                    <option value="">Página inicial</option>
                                                    <option value="">Sobre</option>
                                                    <option value="">Detalhes do curso</option>
                                                    <option value="">Admissão</option>
                                                    <option value="">Blog</option>
                                                    <option value="">Contato</option>
                                                    <option value="">Prêmios</option>
                                                    <option value="">Evento</option>
                                                    <option value="">Seminário</option>
                                                    <option value="">Padrão</option>
												</select>
											</div>
										</div>
										<div class="row">
											<div class="input-field col s12">
												<select>
												<option value="" disabled selected>Selecionar status</option>
                                                <option value="1">Publicar</option>
                                                <option value="1">Pendente</option>
                                                <option value="1">Ativo</option>
                                                <option value="2">Desativar</option>
                                                <option value="3">Excluir</option>
                                                <option value="3">Privare(protegido por senha)</option>
												</select>
											</div>
										</div>
                                        <div class="row">
                                            <div class="input-field col s3">
                                                <i class="waves-effect waves-light btn-large waves-input-wrapper ad-page-pre-btn"><a href="index-2.html" target="_blank">Pré-visualização do pager</a></i>
                                            </div>
                                          <div class="input-field col s2">
                                                <i class="waves-effect waves-light btn-large waves-input-wrapper" style=""><input type="submit" class="waves-button-input" value="Adicionar"></i>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div id="menu1" class="tab-pane fade">
                            <div class="inn-title">
                            <h4>Requisitos, taxas, perfil do aluno e como se inscrever</h4>
                            <p>Hotéis no aeroporto: a maneira certa de começar umas férias curtas</p>
                            </div>
                            <div class="bor ad-cou-deta-h4">
                                <form>
                                <h4>Conteúdo 1:</h4>
									<div class="row">
										<div class="input-field col s12">
											<input type="text" class="validate" required>
											<label>Título:</label>
										</div>
										<div class="input-field col s12">
											<textarea class="materialize-textarea"></textarea>
											<label>Descrições:</label>
										</div>
									</div>
									<h4>Conteúdo 2:</h4>
									<div class="row">
										<div class="input-field col s12">
											<input type="text" class="validate" required>
											<label>Título:</label>
										</div>
										<div class="input-field col s12">
											<textarea class="materialize-textarea"></textarea>
											<label>Descrições:</label>
										</div>
									</div>
									<h4>Conteúdo 3:</h4>
									<div class="row">
										<div class="input-field col s12">
											<input type="text" class="validate" required>
											<label>Título:</label>
										</div>
										<div class="input-field col s12">
											<textarea class="materialize-textarea"></textarea>
											<label>Descrições:</label>
										</div>
									</div>
									<h4>Conteúdo 4:</h4>
									<div class="row">
										<div class="input-field col s12">
											<input type="text" class="validate" required>
											<label>Título:</label>
										</div>
										<div class="input-field col s12">
											<textarea class="materialize-textarea"></textarea>
											<label>Descrições:</label>
										</div>
									</div>

                                    <div class="row">
                                        <div class="input-field col s12">
                                            <i class="waves-effect waves-light btn-large waves-input-wrapper" style=""><input type="submit" class="waves-button-input" value="Adicionar"></i>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div id="menu2" class="tab-pane fade">
                            <div class="inn-title">
                            <h4>Galeria de fotos</h4>
                            <p>Hotéis no aeroporto: a maneira certa de começar umas férias curtas</p></div>
                            <div class="bor">
                                <form action="#">
                                    <div class="file-field input-field">
                                        <div class="btn admin-upload-btn">
                                            <span>Arquivo</span>
                                            <input type="file" multiple="">
                                        </div>
                                        <div class="file-path-wrapper">
                                            <input class="file-path validate" type="text" placeholder="Upload course banner image">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-field col s12">
                                            <i class="waves-effect waves-light btn-large waves-input-wrapper" style=""><input type="submit" class="waves-button-input" value="Carregar"></i>
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>
                        <div id="menu3" class="tab-pane fade">
                            <div class="inn-title">
                            <h4>SEO</h4>
                            <p>Hotéis de aeroporto: a maneira certa de começar umas férias curtas</p>
                            </div>
                            <div class="bor ad-cou-deta-h4">
                                <form>
                                <h4>Meta título e descrições:</h4>
									<div class="row">
										<div class="input-field col s12">
											<input type="text" class="validate" required>
											<label>Título:</label>
										</div>
										<div class="input-field col s12">
											<input type="text" class="validate" required>
											<label>Palavras-chave:</label>
										</div>
										<div class="input-field col s12">
											<textarea class="materialize-textarea"></textarea>
											<label>Descrições:</label>
										</div>
									</div>
									<h4>Estrutura Data 1:</h4>
									<div class="row">
										<div class="input-field col s12">
											<input type="text" class="validate" required>
											<label>Title:</label>
										</div>
										<div class="input-field col s12">
											<textarea class="materialize-textarea"></textarea>
											<label>Descrições:</label>
										</div>
									</div>
									<h4>Rel:editor</h4>
									<div class="row">
										<div class="input-field col s12">
											<input type="text" class="validate" value="https://plus.google.com/u/0/843576742812" required>
											<label>URL do editor do Google:</label>
										</div>
										<div class="input-field col s12">
											<input type="text" class="validate" value="http://websitename.com/" required>
											<label>URL canônico:</label>
										</div>
									</div>
									<h4>Twitter: cartão</h4>
									<div class="row">
										<div class="input-field col s12">
											<input type="text" class="validate" value="http://websitename.com" required>
											<label>Site name:</label>
										</div>
										<div class="input-field col s12">
											<input type="text" class="validate" value="Education Master" required>
											<label>Título do site:</label>
										</div>
										<div class="input-field col s12">
											<textarea class="materialize-textarea"></textarea>
											<label>Descrições:</label>
										</div>
									</div>

                                    <div class="row">
                                        <div class="input-field col s12">
                                            <i class="waves-effect waves-light btn-large waves-input-wrapper" style=""><input type="submit" class="waves-button-input" value="Adicionar"></i>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div id="menu4" class="tab-pane fade">
                            <div class="inn-title">
                            <h4>Informações de contato</h4>
                            <p>Hotéis no aeroporto: a maneira certa de começar umas férias curtas</p></div>
                            <div class="bor">
                                <form>
                                    <div class="row">
                                        <div class="input-field col s6">
                                            <input id="t5-n1" type="text" class="validate">
                                            <label for="t5-n1">Nome</label>
                                        </div>
                                        <div class="input-field col s6">
                                            <input id="t5-n2" type="text" class="validate">
                                            <label for="t5-n2">Alterar nome</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-field col s6">
                                            <input id="t5-n3" type="number" class="validate">
                                            <label for="t5-n3">Telefone</label>
                                        </div>
                                        <div class="input-field col s6">
                                            <input id="t5-n4" type="number" class="validate">
                                            <label for="t5-n4">Móvel</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-field col s12">
                                            <input id="t5-n5" type="email" class="validate">
                                            <label for="t5-n5">E-mail</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-field col s12">
                                            <textarea id="t5-n6" class="materialize-textarea"></textarea>
                                            <label for="t5-n6">Descrições dos anúncios:</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-field col s12">
                                            <i class="waves-effect waves-light btn-large waves-input-wrapper" style=""><input type="submit" class="waves-button-input" value="Upload"></i>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!--Import jQuery before materialize.js-->
    <script src="js/main.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/materialize.min.js"></script>
    <script src="js/custom.js"></script>
</body>


</html>
