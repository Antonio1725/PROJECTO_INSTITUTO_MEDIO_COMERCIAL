<?php

include_once "models/conexao.php";
    
    $conn = $conexao;

    // Captura os dados da primeira etapa
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $perfil_do_aluno = $_POST['descricao_do_perfil_aluno'];
    $como_aplicar_curso = $_POST['como_aplicar_curso'];
    $faculdade = $_POST['faculdade'];
    $inicio = $_POST['inicio'];
    $fim = $_POST['fim'];
    $duracao = $_POST['duracao'];
    $total_vaga = $_POST['total_vaga'];
    $estado = $_POST['estado'];
    
    // Processa os arquivos enviados
    $target_dir = "uploads/";
    $img_doc_path = "";
    $img_banner_path = "";
    
    if (isset($_FILES['img_doc']) && $_FILES['img_doc']['error'] === UPLOAD_ERR_OK) {
        $img_doc_name = basename($_FILES['img_doc']['name']);
        $img_doc_path = $target_dir . uniqid() . "_" . $img_doc_name;
    
        if (!move_uploaded_file($_FILES['img_doc']['tmp_name'], $img_doc_path)) {
            die("Erro ao fazer upload de 'img_doc'.");
        }
    }
    
    if (isset($_FILES['img_banner']) && $_FILES['img_banner']['error'] === UPLOAD_ERR_OK) {
        $img_banner_name = basename($_FILES['img_banner']['name']);
        $img_banner_path = $target_dir . uniqid() . "_" . $img_banner_name;
    
        if (!move_uploaded_file($_FILES['img_banner']['tmp_name'], $img_banner_path)) {
            die("Erro ao fazer upload de 'img_banner'.");
        }
    }
    
    // Captura os dados da segunda etapa (se aplicável)
    $precos = $_POST['precos'] ?? [];
    $disciplinas = $_POST['disciplinas'] ?? [];
    $carga_horaria = $_POST['carga_horaria'] ?? [];
    
    // Inserir o curso (primeira etapa)
    $sql_curso = "INSERT INTO cursos (
        nome, descricao, perfil_do_aluno, como_aplicar_curso, faculdade, inicio, fim, duracao, total_vaga, estado, img_doc, img_banner
    ) VALUES (
        '$nome', '$descricao', '$perfil_do_aluno', '$como_aplicar_curso', '$faculdade', '$inicio', '$fim', '$duracao', '$total_vaga', '$estado', '$img_doc_path', '$img_banner_path'
    )";
    
    if ($conn->query($sql_curso) === TRUE) {
        $curso_id = $conn->insert_id;
    
        // Inserir preços por ano (segunda etapa)
        foreach ($precos as $ano => $preco) {
            $sql_preco = "INSERT INTO precos (curso_id, ano, preco) VALUES ('$curso_id', '$ano', '$preco')";
            $conn->query($sql_preco);
        }
    
        // Inserir anos, semestres e disciplinas (segunda etapa)
        foreach ($disciplinas as $ano => $semestres) {
            $sql_ano = "INSERT INTO anos (curso_id, ano) VALUES ('$curso_id', '$ano')";
            if ($conn->query($sql_ano) === TRUE) {
                $ano_id = $conn->insert_id;
    
                foreach ($semestres as $semestre => $disciplina_list) {
                    $sql_semestre = "INSERT INTO semestres (ano_id, semestre) VALUES ('$ano_id', '$semestre')";
                    if ($conn->query($sql_semestre) === TRUE) {
                        $semestre_id = $conn->insert_id;
    
                        foreach ($disciplina_list as $key => $disciplina) {
                            $carga = $carga_horaria[$ano][$semestre][$key];
                            $sql_disciplina = "INSERT INTO disciplinas (semestre_id, nome, carga_horaria) 
                                               VALUES ('$semestre_id', '$disciplina', '$carga')";
                            $conn->query($sql_disciplina);
                        }
                    }
                }
            }
        }

        echo "<script>
            Swal.fire({
                title: 'Sucesso',
                text: 'Curso cadastrado com sucesso!',
                icon: 'success'
            }).then(() => {
                window.location.href = 'adminListarCursos.php'; // Redireciona para a lista
            });
        </script>";


    } else {

        echo "<script>
Swal.fire({
  title: 'Erro!',
  text: 'Erro ao cadastrar curso',
  icon: 'error'
});
</script>";

    }
    
    $conn->close();
    

    ?>
