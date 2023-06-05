<?php
    //Iniciando seção caso ainda não tenha sido iniciada
    if (!isset($_SESSION)) {
        // Seção iniciada
        session_start();
        // pega o id do usuário atravez do metodo get
        // $user_id = $_GET['user_id'];
        // $trilha = $_GET['trilha'];
    }
    
    // Incluindo o arquivo connect.php
    include_once('connect.php');
    
    // Verifica se as seções de email e senha estão ativas
    if ((!isset($_SESSION['email']) == true)) { // Não estão
        // Não está logado
        unset($_SESSION['email']);

        // Destroi a sessão
        session_destroy();

        // Tentativa de acesso via URL, vai para a página de acesso negado
        header('Location: denied.html');
    }
    
    // Verifica se o botão de sair foi clicado
    if (isset($_POST['sair'])) {
        // Remove todas as variáveis de sessão
        session_unset();
        
        // Destroi a sessão
        session_destroy();
        
        // Redireciona para a página principal
        header('Location: index.html');
        exit();
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/style_trilhas.css">
    <title>Dev Way - Trilha</title>
</head>
<body>
    <nav class="topbar">
        <button class=bt_voltar>Voltar</button>
        <div class="barra_progresso"></div>
        <button class="bt_sair">sair</button>
    </nav>
    
    <div class="content">
        <?php
            $nomeTrilha = $_GET["trilha"];
            echo "<h1 class='titleTrilha'>" . $nomeTrilha . ":" ."</h1>";
            // pesquisando o nome dos temas
            $pesquisa_temas = "SELECT * FROM temas  WHERE trilha_id = 4";
            $resultado_pesquisa_temas = mysqli_query($conexao, $pesquisa_temas);
            $_SESSION["idTrilha"] = 4;
            // pesuquisando o nome dos cursos
            $pesquisa_cursos = "SELECT * FROM cursos";
            $resultado_pesquisa_cursos = mysqli_query($conexao, $pesquisa_cursos);

            // Verifica se a consulta retornou resultados para TEMAS
            if (mysqli_num_rows($resultado_pesquisa_temas) > 0) {
                // conta tipo o id do tema 
                $contador = 1;
                // Loop para percorrer os resultados e exibir os temas em divs
                while ($row = $resultado_pesquisa_temas->fetch_assoc()) {
                    $idTema = $row["id"];
                    $nome = $row["nome"];
                    // exibição do tema
                    echo "<div class='tema_conteiner'>";
                    echo "<h3 class='trilhas_nome'>" . $nome . "</h3>";

                    //filtra os cursos pro tema
                    $pesquisa_filtrar_cursos = "SELECT * FROM cursos WHERE tema_id = $idTema";
                    $resultado_filtrar_cursos = mysqli_query($conexao, $pesquisa_filtrar_cursos);
                            
                    //verifica se retornou resultador dos cursos
                    if (mysqli_num_rows($resultado_filtrar_cursos) > 0) {
                    // loop para listar os cursos
                        $contadorCursos = 1;
                        while ($row_curso = $resultado_filtrar_cursos->fetch_assoc()) {
                            // captura os dados
                            $curso_link = $row_curso['link'];
                            $cursoTemaId = $row_curso['tema_id'];
                            $finish = $row_curso['finish'];
                            $idCurso = $row_curso['id'];
                            // exibição
                            echo "<div class='courseList'>";
                            if($finish == 1) {
                                echo "<input type='checkbox' name='curso'  data-curso-id='$idCurso' checked>";
                                echo "<a class=nome_curso href=" . $curso_link . " for='curso' target=\"\_blank\"\">" . $curso_nome ."</a>";

                            } else {
                                echo "<input type='checkbox' name='curso' data-curso-id='$idCurso'>";
                                echo "<a class=nome_curso href=" . $curso_link . " for='curso' target=\"\_blank\"\">" . $curso_nome ."</a>";
                            }
                            echo "</div>";
                            $contadorCursos++;
                        }
                    } else {
                        echo '<script>alert("Nenhum curso encontrado");</script>';
                    }
                    // fecha a div do tema
                    echo "</div>";
                    // soma +1 no tema para calcular o próximo 
                    $contador++;
                }
            } else {
                echo '<script>alert("Nenhuma trilha encontrada");</script>';
            }

        ?>
    </div>
    <script src="javascript/system.js"></script>
</body>
</html>