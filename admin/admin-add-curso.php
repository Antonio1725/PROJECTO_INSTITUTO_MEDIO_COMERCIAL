<?php
include_once "header.php";
include_once "container_pricipal.php";
include_once "menulateral.php";
include_once "models/Cursos.php";
include_once "models/conexao.php";
if (isset($_SESSION["nome_completo"])) {
?>
    <style>
        .step {
            display: none;
        }

        .step.active {
            display: block;
        }
    </style>
    <style>
        label {
            font-weight: bold;
        }
    </style>

    <!--== BODY INNER CONTAINER ==-->
    <div class="sb2-2">
        <!--== breadcrumbs ==-->
        <div class="sb2-2-2">
            <ul>
                <li><a href="#"><i class="fa fa-home" aria-hidden="true"></i> Painel</a>
                </li>
                <li class="active-bre"><a href="#"> Adicionar Curso</a>
                </li>

            </ul>
        </div>



        <!--== Adicionar Curso ==-->
        <div class="sb2-2-3">
            <div class="row">
                <div class="col-md-12">
                    <div class="box-inn-sp admin-form">



                        <div class="container mt-5">
                            <div class="inn-title">
                                <h1 class="mb-4" style="color:white">Adicionar Cursos</h1>
                            </div>

                            <form id="multiStepForm" class="col-md-10" method="post" action="cadastar_cursos.php" enctype="multipart/form-data">
                                <!-- Etapa 1 -->
                                <div class="step active" id="step-1">
                                    <h3>Etapa 1: Dados Inicias</h3>
                                    <div class="mb-3">
                                        <label for="nome" class="form-label">Nome do Curso</label>
                                        <input id="nome" name="nome" type="text" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="descricao" class="form-label">Descrição</label>
                                        <textarea name="descricao" class="form-control" required></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="descricao_do_perfil_aluno" class="form-label">Perfil do aluno</label>
                                        <textarea name="descricao_do_perfil_aluno" class="form-control" required></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="como_aplicar_curso" class="form-label">Como aplicar este curso:</label>
                                        <textarea name="como_aplicar_curso" class="form-control" required></textarea>
                                    </div>


                                    <div class="mb-3">
                                        <label for="faculdade" class="form-label">Faculdade / Pós-Graduação</label>
                                        <select id="faculdade" name="faculdade">
                                            <option value="" disabled selected>Selecione uma faculdade</option>
                                            <option>FACULDADE DE MEDICINA</option>
                                            <option>FACULDADE DE CIÊNCIAS DA SAÚDE</option>
                                            <option>FACULDADE DE CIÊNCIAS EXACTAS</option>
                                            <option>FACULDADE DE CIÊNCIAS SOCIAIS E HUMANAS</option>
                                            <option>PÓS-GRADUAÇÃO ACADÉMICA</option>
                                            <option>PÓS-GRADUAÇÃO PROFISSIONAL</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="inicio" class="form-label">Data de Início</label>
                                        <input id="inicio" name="inicio" type="date" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="fim" class="form-label">Data de Término</label>
                                        <input id="fim" name="fim" type="date" class="form-control">
                                    </div>


                                    <div class="mb-3">
                                        <label for="duracao" class="">Duração</label>
                                        <input id="duracao" name="duracao" type="text" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="total_vaga" class="">Total de Vaga</label>
                                        <input id="total_vaga" name="total_vaga" type="number" required>
                                    </div>
                                    <div class="mb-3">
                                        <select name="estado">
                                            <option value="" disabled selected>Selecione o status</option>
                                            <option value="Ativo">Ativo</option>
                                            <option value="Desativado">Desativado</option>
                                        </select>
                                    </div>

                                    <button type="button" class="btn btn-primary next">Próximo</button>
                                </div>

                                <!-- Etapa 2 -->
                                <div class="step" id="step-2">
                                    <h3>Etapa 2: Programa do Curso</h3>


                                    <!-- Container de Anos -->
                                    <div id="anos-container">
                                        <h4>Anos e Preços</h4>
                                        <div class="ano-item mb-4" data-ano="1">
                                            <h5 class="text-primary">Ano 1</h5>
                                            <div class="mb-3">
                                                <label for="preco_ano_1" class="form-label">Preço do Ano 1</label>
                                                <input type="text" id="preco_ano_1" name="precos[1]" class="form-control moeda" required>

                                                <script>
                                                    function formatarMoedaClasse() {
                                                        const elementos = document.querySelectorAll('.moeda'); // Seleciona todos os elementos com a classe 'moeda'

                                                        elementos.forEach(elemento => {
                                                            let valor = elemento.value;

                                                            valor = valor + '';
                                                            valor = parseInt(valor.replace(/[\D]+/g, ''));
                                                            if (isNaN(valor)) {
                                                                elemento.value = '';
                                                                return;
                                                            }

                                                            valor = valor + '';
                                                            valor = valor.replace(/([0-9]{2})$/g, ",$1");

                                                            if (valor.length > 6) {
                                                                valor = valor.replace(/([0-9]{3}),([0-9]{2}$)/g, ".$1,$2");
                                                            }

                                                            elemento.value = valor;
                                                        });
                                                    }

                                                    // Adiciona evento automaticamente aos inputs com a classe 'moeda'
                                                    document.addEventListener('input', function(event) {
                                                        if (event.target.classList.contains('moeda')) {
                                                            formatarMoedaClasse();
                                                        }
                                                    });
                                                </script>

                                            </div>

                                            <!-- Semestres -->
                                            <div class="semestres-container">
                                                <div class="semestre-item mb-3" data-semestre="1">
                                                    <h6 class="text-secondary">Semestre 1</h6>

                                                    <!-- Disciplinas -->
                                                    <div class="disciplinas-container">
                                                        <div class="disciplina-item row align-items-end mb-2">
                                                            <div class="col-md-5">
                                                                <label for="disciplina_nome" class="form-label">Nome da Disciplina</label>
                                                                <input type="text" name="disciplinas[1][1][]" class="form-control" required>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="carga_horaria" class="form-label">Carga Horária</label>
                                                                <input type="number" name="carga_horaria[1][1][]" class="form-control" required>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label class="form-label" style="color: white">Carga Horária</label>
                                                                <button type="button" class="btn btn-danger btn-sm remove-disciplina">
                                                                    <span class="fa fa-trash" style="color: #ffffff"></span>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button type="button" class="btn btn-success btn-sm add-disciplina">Adicionar Disciplina</button> <br>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-secondary btn-sm add-semestre">Adicionar Semestre</button> <br>
                                        </div>
                                    </div>

                                    <button type="button" class="btn btn-primary btn-sm add-ano">Adicionar Ano</button> <br>

                                    <br>
                                    <br>
                                    <div class="mb-3">
                                        <label for="img_doc" class="form-label">Anexar Programa</label>
                                        <input id="img_doc" name="img_doc" type="file" class="form-control" accept=".pdf" required>
                                    </div>
                                    <hr>
                                    <button type="button" class="btn btn-secondary prev">Voltar</button>
                                    <button type="button" class="btn btn-primary next">Próximo</button>
                                </div>
                                <script>
                                    document.addEventListener("DOMContentLoaded", function() {
                                        let anoCount = 1;

                                        // Adicionar novo ano
                                        document.querySelector(".add-ano").addEventListener("click", function() {
                                            anoCount++;
                                            const anoContainer = document.createElement("div");
                                            anoContainer.classList.add("ano-item", "mb-4");
                                            anoContainer.setAttribute("data-ano", anoCount);
                                            anoContainer.innerHTML = `
            <h5 class="text-primary">Ano ${anoCount}</h5>
            <div class="mb-3">
                <label for="preco_ano_${anoCount}" class="form-label">Preço do Ano ${anoCount}</label>
                <input type="number" id="preco_ano_${anoCount}" name="precos[${anoCount}]" class="form-control" required>
            </div>
            <div class="semestres-container">
                <div class="semestre-item mb-3" data-semestre="1">
                    <h6 class="text-secondary">Semestre 1</h6>
                    <div class="disciplinas-container">
                        <div class="disciplina-item row align-items-end mb-2">
                            <div class="col-md-5">
                                <label for="disciplina_nome" class="form-label">Nome da Disciplina</label>
                                <input type="text" name="disciplinas[${anoCount}][1][]" class="form-control" required>
                            </div>
                            <div class="col-md-3">
                                <label for="carga_horaria" class="form-label">Carga Horária</label>
                                <input type="number" name="carga_horaria[${anoCount}][1][]" class="form-control" required>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-danger btn-sm remove-disciplina">
                                    Remover
                                </button>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-success btn-sm add-disciplina">Adicionar Disciplina</button>
                </div>
            </div>
            <button type="button" class="btn btn-secondary btn-sm add-semestre">Adicionar Semestre</button>
        `;
                                            document.getElementById("anos-container").appendChild(anoContainer);
                                        });

                                        // Adicionar novo semestre
                                        document.addEventListener("click", function(e) {
                                            if (e.target.classList.contains("add-semestre")) {
                                                const anoItem = e.target.closest(".ano-item");
                                                const semestresContainer = anoItem.querySelector(".semestres-container");
                                                const ano = anoItem.getAttribute("data-ano");
                                                const semestreCount = semestresContainer.children.length + 1;

                                                const semestreItem = document.createElement("div");
                                                semestreItem.classList.add("semestre-item", "mb-3");
                                                semestreItem.setAttribute("data-semestre", semestreCount);
                                                semestreItem.innerHTML = `
                <h6 class="text-secondary">Semestre ${semestreCount}</h6>
                <div class="disciplinas-container">
                    <div class="disciplina-item row align-items-end mb-2">
                        <div class="col-md-5">
                            <label for="disciplina_nome" class="form-label">Nome da Disciplina</label>
                            <input type="text" name="disciplinas[${ano}][${semestreCount}][]" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label for="carga_horaria" class="form-label">Carga Horária</label>
                            <input type="number" name="carga_horaria[${ano}][${semestreCount}][]" class="form-control" required>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger btn-sm remove-disciplina">
                                Remover
                            </button>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-success btn-sm add-disciplina">Adicionar Disciplina</button>
            `;
                                                semestresContainer.appendChild(semestreItem);
                                            }
                                        });

                                        // Adicionar nova disciplina
                                        document.addEventListener("click", function(e) {
                                            if (e.target.classList.contains("add-disciplina")) {
                                                const semestreItem = e.target.closest(".semestre-item");
                                                const disciplinasContainer = semestreItem.querySelector(".disciplinas-container");
                                                const ano = semestreItem.closest(".ano-item").getAttribute("data-ano");
                                                const semestre = semestreItem.getAttribute("data-semestre");

                                                const disciplinaItem = document.createElement("div");
                                                disciplinaItem.classList.add("disciplina-item", "row", "align-items-end", "mb-2");
                                                disciplinaItem.innerHTML = `
                <div class="col-md-5">
                    <label for="disciplina_nome" class="form-label">Nome da Disciplina</label>
                    <input type="text" name="disciplinas[${ano}][${semestre}][]" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label for="carga_horaria" class="form-label">Carga Horária</label>
                    <input type="number" name="carga_horaria[${ano}][${semestre}][]" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger btn-sm remove-disciplina">
                        Remover
                    </button>
                </div>
            `;
                                                disciplinasContainer.appendChild(disciplinaItem);
                                            }
                                        });

                                        // Remover disciplina
                                        document.addEventListener("click", function(e) {
                                            if (e.target.classList.contains("remove-disciplina")) {
                                                e.target.closest(".disciplina-item").remove();
                                            }
                                        });
                                    });
                                </script>

                                <!-- Etapa 3 -->
                                <div class="step" id="step-3">
                                    <h3>Etapa 3: Imagem do Banner</h3>
                                    <div class="mb-3">
                                        <label for="img_banner" class="form-label">Imagem do Banner</label>
                                        <input id="img_banner" name="img_banner" type="file" class="form-control" required>
                                    </div>
                                    <button type="button" class="btn btn-secondary prev">Voltar</button>
                                    <button type="submit" name="cadastrar" class="btn btn-success">Cadastrar</button>
                                </div>


                            </form>
                        </div>

                        <script>
                            const steps = document.querySelectorAll(".step");
                            let currentStep = 0;

                            // Botões "Próximo" e "Voltar"
                            document.querySelectorAll(".next").forEach(button => {
                                button.addEventListener("click", () => {
                                    if (validateStep(currentStep)) {
                                        steps[currentStep].classList.remove("active");
                                        currentStep++;
                                        steps[currentStep].classList.add("active");
                                    }
                                });
                            });

                            document.querySelectorAll(".prev").forEach(button => {
                                button.addEventListener("click", () => {
                                    steps[currentStep].classList.remove("active");
                                    currentStep--;
                                    steps[currentStep].classList.add("active");
                                });
                            });

                            // Validação de cada etapa
                            function validateStep(step) {
                                const inputs = steps[step].querySelectorAll("input, textarea");
                                for (const input of inputs) {
                                    if (!input.checkValidity()) {
                                        alert("Preencha todos os campos obrigatórios!");
                                        return false;
                                    }
                                }
                                return true;
                            }

                            // Submeter o formulário
                            //document.getElementById("multiStepForm").addEventListener("submit", (e) => {
                            //   e.preventDefault();
                            //  alert("Formulário enviado com sucesso!");
                            //});
                        </script>


                        </div>
                    </div>
                </div>
            </div>
        </div>




    </div>

    <?php




    ?>





    <!--Import jQuery before materialize.js-->
    <script src="js/main.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/materialize.min.js"></script>
    <script src="js/custom.js"></script>
    </body>



    </html>
<?php
} else {
    echo "<script>window.location='index.php'</script>";
}




?>