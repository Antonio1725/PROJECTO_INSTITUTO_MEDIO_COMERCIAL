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
                        <li><a href="index-2.html"><i class="fa fa-home" aria-hidden="true"></i> Início</a>
                        </li>
                        <li class="active-bre"><a href="#"> Painel</a>
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
                                <div class="inn-title">
                                    <h4>Configuração do site</h4>
                                    <p>Here you can edit your website basic details URL, Phone, Email, Address, User and password and more</p>
                                </div>
                                <div class="tab-inn">
                                    <form>
                                        <div class="row">
                                            <div class="input-field col s6">
                                                <input id="first_name" type="text" value="Título do site" class="validate" required>
                                                <label for="first_name" class="">Título do site</label>
                                            </div>
                                            <div class="input-field col s6">
                                                <input id="last_name" type="text" value="Descrições do site" class="validate" required>
                                                <label for="last_name" class="">Descrições do site</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input-field col s6">
                                                <input id="phone" type="number" value="Telefone" class="validate" required>
                                                <label for="phone">Telefone</label>
                                            </div>
                                            <div class="input-field col s6">
                                                <input type="text" class="validate" value="E-mail" required>
                                                <label for="cphone" class="">E-mail</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input-field col s6">
                                                <input id="city" type="text" value="Localização" class="validate">
                                                <label for="city" class="">Localização</label>
                                            </div>
                                            <div class="input-field col s6">
                                                <input id="country" type="text" value="País" class="validate">
                                                <label for="country" class="">País</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input-field col s6">
                                                <input id="password" type="password" value="Senha" class="validate">
                                                <label for="password" class="">Senha</label>
                                            </div>
                                            <div class="input-field col s6">
                                                <input id="password1" type="password" value="Confirme sua senha" class="validate">
                                                <label for="password1" class="">Confirme sua senha</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input-field col s12">
                                                <input type="text" value="Website" class="validate">
                                                <label>Website</label>
                                            </div>
                                            <div class="input-field col s12">
                                                <input type="text" value="Blog do site" class="validate">
                                                <label>Blog do site</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input-field col s12">
                                                <i class="waves-effect waves-light btn-large waves-input-wrapper" style="">Cadastrar<input type="submit" class="waves-button-input"></i>
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

    <!--Import jQuery before materialize.js-->
    <script src="js/main.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/materialize.min.js"></script>
    <script src="js/custom.js"></script>
</body>


</html>
