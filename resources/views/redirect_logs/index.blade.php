<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Átirányítások - URL Shortener</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">

        <style>
            body {
                font-family: 'Nunito', sans-serif;
            }
        </style>
    </head>
    <body class="antialiased">
        <section class="mt-5">
            <div class="container">
                <div class="row pt-4">
                    <div class="col">
                        <table class="table">
                            <thead>
                              <tr>
                                <th scope="col">#</th>
                                <th scope="col">Dátum</th>
                                <th scope="col">Ip cím</th>
                                <th scope="col">Böngésző adatok</th>
                                <th scope="col">Cél url</th>
                                <th scope="col">Slug</th>
                                <th scope="col">Db</th>
                              </tr>
                            </thead>
                            <tbody id="redirectLogsBody"></tbody>
                          </table>
                    </div>
                </div>
            </div>  
        </section>
        <script src="http://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous">
        </script>
        <script>
            jQuery(document).ready(function(){
                function getRedirectLogs(filter = ''){
                    let getRedirectLogsUrl = '/graphql?query={redirect_logs {ip_address,browser,date,slug,link,total}}'
                    
                    $('#redirectLogsBody').html('')
                    jQuery.ajax({
                        url: getRedirectLogsUrl,
                        method: 'get',
                        success: function(result){
                            let redirectLogs = result.data.redirect_logs
                            for(let i = 0; i < redirectLogs.length; i++){
                                makeTableRow(i+1,
                                    redirectLogs[i]['date'],
                                    redirectLogs[i]['ip_address'],
                                    redirectLogs[i]['browser'],
                                    redirectLogs[i]['link'],
                                    redirectLogs[i]['slug'],
                                    redirectLogs[i]['total'],
                                    'redirectLogsBody')
                            }
                        }
                    });
                }
                getRedirectLogs()
                
                function makeTableRow(id, created_at, ip_address, browser, link, slug, total, appendTo){
                    let $id = $('<th>').html(id);
                    let $created_at = $('<td>').html(created_at);
                    let $ip_address = $('<td>').html(ip_address);
                    let $browser = $('<td>').html(browser);
                    let $link = $('<td>').html(link);
                    let $slug = $('<td>').html(slug);
                    let $total = $('<td>').html(total);
                    let $row = $('<tr>');

                    $id.appendTo($row);
                    $created_at.appendTo($row);
                    $ip_address.appendTo($row);
                    $browser.appendTo($row);
                    $link.appendTo($row);
                    $slug.appendTo($row);
                    $total.appendTo($row);
                    
                    $row.appendTo('#' + appendTo)
                }
            });
        </script>
    </body>
</html>
