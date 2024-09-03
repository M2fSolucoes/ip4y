<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Email Notification</title>
  <style>
    @media only screen and (min-width: 620px) {
      .u-row { width: 600px !important; }
      .u-row .u-col { vertical-align: top; }
      .u-row .u-col-100 { width: 600px !important; }
    }
    @media (max-width: 620px) {
      .u-row-container { max-width: 100% !important; padding: 0 !important; }
      .u-row .u-col { min-width: 320px !important; max-width: 100% !important; display: block !important; }
      .u-row, .u-col { width: 100% !important; }
      .u-col > div { margin: 0 auto; }
    }
    body { margin: 0; padding: 0; background-color: #ecedf1; color: #000; }
    table, tr, td { vertical-align: top; border-collapse: collapse; }
    * { line-height: inherit; }
  </style>
</head>
<body>
  <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #ecedf1;">
    <tr>
      <td>
        <div class="u-row-container" style="padding: 0; background-color: transparent;">
          <div class="u-row" style="margin: 0 auto; max-width: 600px; background-color: #ffffff;">
            <div style="display: table; width: 100%; height: 100%;">
              <div class="u-col u-col-100" style="min-width: 600px;">
                <div style="height: 100%; width: 100%; background-color: #ffffff; padding: 30px 20px 10px;">
                  <h1 style="font-family: Arvo, serif; font-size: 30px; line-height: 120%;">Olá, temos uma nova tarefa para você</h1>
                  <div style="font-family: Raleway, sans-serif; font-size: 14px; color: #7e8c8d; line-height: 140%;">
                    <p>Olá, {{$user->name}} , uma nova tarefa foi designada a você atente-se aos prazos de entrega e ao descritivo , qualquer dúvida entre en contato com seiu lider!</p>
                    <ul>
                      <li>Projeto: <b>{{$project->title}} | {{$project->project_code}}</b> </li>
                      <li>Tarefa : {{$task->title}}</li>
                      <li>Entrega: {{$end_date}}</li>
                      </ul>
                    <p>Contamos com você</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </td>
    </tr>
  </table>
</body>
</html>
