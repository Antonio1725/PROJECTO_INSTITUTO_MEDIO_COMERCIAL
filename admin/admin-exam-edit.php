<?php
include_once "header.php";
include_once "container_pricipal.php";
include_once "menulateral.php";
?>


                     <!--== CORPO INTERNO RECIPIENTE ==-->
                <div class="sb2-2">
                    <!--== breadcrumbs ==-->
                <div class="sb2-2-2">
                    <ul>
                        <li><a href="index-2.html"><i class="fa fa-home" aria-hidden="true"></i> Painel</a>
                        </li>
                        <li class="active-bre"><a href="#"> Editar Exame</a>
                        </li>

                    </ul>
                </div>

                <!--== User Details ==-->
                <div class="sb2-2-3">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box-inn-sp admin-form">
				<div class="sb2-2-add-blog sb2-2-1">
                    <h2>Editar Exame</h2>
                    <p>XXXXXXXXXX:</p>

                    <ul class="nav nav-tabs tab-list">
                        <li class="active"><a data-toggle="tab" href="#home" aria-expanded="true"><i class="fa fa-sticky-note-o" aria-hidden="true"></i> <span>Exame 1</span></a>
                        </li>
                        <li class=""><a data-toggle="tab" href="#menu1" aria-expanded="false"><i class="fa fa-sticky-note-o" aria-hidden="true"></i> <span>Exame 2</span></a>
                        </li>
                        <li class=""><a data-toggle="tab" href="#menu2" aria-expanded="false"><i class="fa fa-sticky-note-o" aria-hidden="true"></i> <span>Exame 3</span></a>
                        </li>
                        <li class=""><a data-toggle="tab" href="#menu3" aria-expanded="false"><i class="fa fa-sticky-note-o" aria-hidden="true"></i> <span>Exame 4</span></a>
                        </li>
                        <li class=""><a data-toggle="tab" href="#menu4" aria-expanded="false"><i class="fa fa-sticky-note-o" aria-hidden="true"></i> <span>Exame 5</span></a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div id="home" class="tab-pane fade active in">
                            <div class="box-inn-sp">
                                <div class="inn-title">
                                    <h4>Detalhes do Exame</h4>
                                    <p>XXXXXXXXXXXXXXXXXX</p>
                                </div>
                                <div class="bor">
									<form>
                                        <div class="row">
                                            <div class="input-field col s12">
                                                <input type="text" value="Semestre 1" class="validate">
                                                <label class="">Nome do exame principal</label>
                                            </div>
                                            <div class="input-field col s12">
                                                <input type="text" value="Aulas de treinamento para exames do conselho" class="validate">
                                                <label>Nome do exame</label>
                                            </div>
											<div class="input-field col s12">
                                                <input type="text" value="12 de maio de 2018" class="validate">
                                                <label>Data</label>
                                            </div>
											<div class="input-field col s12">
                                                <input type="text" value="10:00 " class="validate">
                                                <label>Hora de início</label>
                                            </div>
											<div class="input-field col s12">
                                                <input type="text" value="03:00 horas" class="validate">
                                                <label>Duração</label>
                                            </div>
                                        </div>
                                            <div class="row">
                                                <div class="input-field col s12">
                                                    <select>
									  <option value="" disabled selected>Selecione o status</option>
									  <option value="1">Ativo</option>
                                      <option value="2">Desativado</option>
                                      <option value="3">Excluir</option>
									</select>
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
                        </div>
                        <div id="menu1" class="tab-pane fade">
                        <div class="inn-title">
                            <h4>Detalhes do exame</h4>
                            <p>Hotéis no aeroporto: a maneira certa de começar umas férias curtas</p>
                            </div>
                            <div class="bor ad-cou-deta-h4">
									<form>
                                        <div class="row">
                                            <div class="input-field col s12">
                                                <input type="text" value="Semester 1" class="validate">
                                                <label class="">Main exam name</label>
                                            </div>
                                            <div class="input-field col s12">
                                                <input type="text" value="Board Exam Training Classes" class="validate">
                                                <label>Exam name</label>
                                            </div>
											<div class="input-field col s12">
                                                <input type="text" value="12 may 2018" class="validate">
                                                <label>Date</label>
                                            </div>
											<div class="input-field col s12">
                                                <input type="text" value="10:00AM" class="validate">
                                                <label>Start time</label>
                                            </div>
											<div class="input-field col s12">
                                                <input type="text" value="03:00hrs" class="validate">
                                                <label>Duration</label>
                                            </div>
                                        </div>
                                            <div class="row">
                                                <div class="input-field col s12">
                                                    <select>
									  <option value="" disabled selected>Select Status</option>
									  <option value="1">Active</option>
									  <option value="2">De-Active</option>
									  <option value="3">Delete</option>
									</select>
                                                </div>
                                            </div>
                                        <div class="row">
                                            <div class="input-field col s12">
                                                <i class="waves-effect waves-light btn-large waves-input-wrapper" style=""><input type="submit" class="waves-button-input" value="Submit"></i>
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
