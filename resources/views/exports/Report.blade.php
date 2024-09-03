<!DOCTYPE html>
<html>
<head>
    <title>Relatório de Projetos e Tarefas</title>
</head>
<body>
    <h1 style="text-align: center; font-family: Arial, sans-serif;">Relatório de Projetos e Tarefas</h1>
    <table style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif;">
        <thead>
            <tr>
                <th style="border: 1px solid #000; padding: 8px; background-color: #d3d3d3;">Tipo</th>
                <th style="border: 1px solid #000; padding: 8px; background-color: #d3d3d3;">Título</th>
                <th style="border: 1px solid #000; padding: 8px; background-color: #d3d3d3;">Descrição</th>
                <th style="border: 1px solid #000; padding: 8px; background-color: #d3d3d3;">Status</th>
                <th style="border: 1px solid #000; padding: 8px; background-color: #d3d3d3;">Data de Conclusão</th>
                <th style="border: 1px solid #000; padding: 8px; background-color: #d3d3d3;">Email</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reports as $report)
                <!-- Linha do Projeto -->
                <tr style="background-color: #f2f2f2; font-weight: bold;">
                    <td style="border: 1px solid #000; padding: 8px;">Projeto</td>
                    <td style="border: 1px solid #000; padding: 8px;">{{ $report['project']['title'] }}</td>
                    <td style="border: 1px solid #000; padding: 8px;" colspan="2">{{ $report['project']['description'] }}</td>

                    <td style="border: 1px solid #000; padding: 8px;">{{ date('d/m/Y', strtotime($report['project']['end_date'])) }}</td>
                    <td style="border: 1px solid #000; padding: 8px;">-</td>
                </tr>

                @foreach($report['tasks'] as $task)
                    <!-- Linha da Tarefa -->
                    <tr style="background-color: #e6e6e6; padding-left: 20px;">
                        <td style="border: 1px solid #000; padding: 8px;">Tarefa</td>
                        <td style="border: 1px solid #000; padding: 8px;">{{ $task['title'] }}</td>
                        <td style="border: 1px solid #000; padding: 8px;">{{ $task['description'] }}</td>
                        <td style="border: 1px solid #000; padding: 8px;">{{ $task['status'] }}</td>
                        <td style="border: 1px solid #000; padding: 8px;">{{  date('d/m/Y', strtotime($task['end_date'])) }}</td>
                        <td style="border: 1px solid #000; padding: 8px;">-</td>
                    </tr>

                    <tr style="background-color: #e6e6e6; padding-left: 20px;">
                        <td style="border: 1px solid #000; padding: 8px;" colspan="6">Atribuidos</td>
                    </tr>

                    @foreach($task['users'] as $user)
                        <!-- Linha do Usuário -->
                        <tr style="background-color: #ffffff; padding-left: 40px;">
                            <td style="border: 1px solid #000; padding: 8px;" colspan="5">{{ $user['name'] }}</td>
                            <td style="border: 1px solid #000; padding: 8px;">{{ $user['email'] }}</td>
                        </tr>
                    @endforeach
                @endforeach
            @endforeach
        </tbody>
    </table>
</body>
</html>
