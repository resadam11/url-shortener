<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>URL Shortener</title>

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
            .disabled{
                pointer-events: none;
            }
            .more-btn{
                display: block;
                width: fit-content;
            }
        </style>
    </head>
    <body class="antialiased">
        <section class="mt-5">
            <div class="container">
                <div class="row">
                  <div class="col-sm">
                    <h3>Hozzáadás</h3>
                    <form class="row align-items-start">
                        <div class="form-group col-3">
                          <input type="text" class="form-control" id="link" placeholder="Cél url" required>
                          <div class="invalid-feedback">
                            Cél url megadása kötelező!
                          </div>
                        </div>
                        <div class="form-group col-3">
                          <input type="text" class="form-control" id="slug" placeholder="Slug">
                        </div>
                        <div class="form-group col-3">
                            <input type="number" class="form-control" id="status_code" placeholder="Státusz kód">
                        </div>
                        <div class="form-group col-3">
                            <button id="formSubmit" type="submit" class="btn btn-primary col-4 w-100">Hozzáad</button>
                        </div>
                      </form>
                  </div>
                </div>
                <hr class="my-5">
                <div class="row">
                    <div class="col">
                        <h3>Lekérés</h3>
                        <form class="row align-items-end">
                            <div class="form-group col-4">
                              <input type="number" class="form-control" id="getOneId" placeholder="Id">
                            </div>
                            <div class="form-group col-5">
                                <input type="text" class="form-control" id="getOneSlug" placeholder="Slug">
                              </div>
                            <div class="form-group col-3">
                                <button id="getOneSubmit" type="submit" class="btn btn-primary col-4 w-100">Lekér</button>
                            </div>
                          </form>
                    </div>
                </div>
                <div class="row pt-4">
                    <div class="col">
                        <table class="table">
                            <thead>
                              <tr>
                                <th scope="col">#</th>
                                <th scope="col">Cél url</th>
                                <th scope="col">Slug</th>
                                <th scope="col">Státusz kód</th>
                              </tr>
                            </thead>
                            <tbody id="shortLinkBody"></tbody>
                          </table>
                    </div>
                </div>
                <hr class="my-5">
                <div class="row">
                    <div class="col">
                        <h3>Szűrés</h3>
                        <form class="row align-items-end">
                            <div class="form-group col-9">
                              <input type="text" class="form-control" id="filter" placeholder="Cél url">
                            </div>
                            <div class="form-group col-3">
                                <button id="filterSubmit" type="submit" class="btn btn-primary col-4 w-100">Szűrés</button>
                            </div>
                          </form>
                    </div>
                </div>
                <div class="row pt-4">
                    <div class="col">
                        <table class="table">
                            <thead>
                                <tr>
                                <th scope="col">#</th>
                                <th scope="col">Cél url</th>
                                <th scope="col">Slug</th>
                                <th scope="col">Státusz kód</th>
                                </tr>
                            </thead>
                            <tbody id="shortLinksBody" data-filter=""></tbody>
                        </table>
                        <nav aria-label="...">
                            <ul id="pagination" class="pagination">
                                <li id="paginationPrev" class="page-item disabled">
                                    <a class="page-link" href="#">Previous</a>
                                </li>
                                <div id="paginationList" data-current="1" class="d-flex"></div>
                                <li id="paginationNext" class="page-item">
                                    <a class="page-link" href="#">Next</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <hr class="my-5">
                <div class="row pb-5">
                    <div class="col">
                        <h3>Átirányítások</h3>
                        <div class="row">
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
                                <a class="btn btn-primary mx-auto mt-4 more-btn" target="_blank" href="{{route('redirect_logs.index')}}">Megnyitás új oldalon</a>
                            </div>
                        </div>
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
                function getShortLinks(page = 1, filter = ''){
                    let getShortLinksUrl = '/graphql?query={short_links(limit: 10, page: ?page ?filter) {data {id,slug,link} total,per_page}}'
            
                    getShortLinksUrl = getShortLinksUrl.replace('?page', page)

                    filter = filter == '' ? $('#shortLinksBody').data('filter') : filter
                    let filterText = filter == '' ? '' : ', filter: "' + filter + '"'
                    getShortLinksUrl = getShortLinksUrl.replace('?filter', filterText)

                    $('#shortLinksBody').html('')
                    
                    jQuery.ajax({
                        url: getShortLinksUrl,
                        method: 'get',
                        success: function(result){
                            let shortLinks = result.data.short_links.data
                            for(let i = 0; i < shortLinks.length; i++){
                                makeTableRow(shortLinks[i]['id'],
                                    shortLinks[i]['link'],
                                    shortLinks[i]['slug'],
                                    shortLinks[i]['status_code'],
                                    'shortLinksBody')
                            }

                            makePagination(result.data.short_links.per_page, result.data.short_links.total, page)
                        }
                    });
                }
                getShortLinks()
                
                function makeTableRow(id, link, slug, status_code, appendTo){
                    let $id = $('<th>').html(id);
                    let $link = $('<td>').html(link);
                    let $slugLink = $('<a target="_blank" href="' + slug + '"></a>').html(slug);
                    let $slug = $('<td>');
                    $slugLink.appendTo($slug);
                    let $statusCode = $('<td>').html(status_code);
                    let $row = $('<tr>');

                    $id.appendTo($row);
                    $link.appendTo($row);
                    $slug.appendTo($row);
                    $statusCode.appendTo($row);
                    
                    $row.appendTo('#' + appendTo)
                }

                function makePagination(per_page, total, active){
                    $('#paginationList').html('')
                    let length = Math.ceil(total/per_page)
                    for(let i = 0; i < length; i++){
                        let $li = $('<li>').addClass('page-item');
                        if(i+1 == active){
                            $li.addClass('active')
                        }
                        let $a = $('<a href="#" data-page=' + (i+1) + '></a>').addClass('page-link');
                        $a.html(i+1)
                        $a.appendTo($li)
                        $li.appendTo('#paginationList')
                    }

                    if(active > 1){
                        $('#paginationPrev').removeClass('disabled')
                    }
                    else{
                        $('#paginationPrev').addClass('disabled')
                    }

                    if(length == active){
                        $('#paginationNext').addClass('disabled')
                    }
                    else{
                        $('#paginationNext').removeClass('disabled')
                    }
                }

                $('#paginationList').click(function(e){
                    if(e.target.classList.contains('page-link')){
                        e.preventDefault();
                        let page = e.target.dataset.page
                        $('#paginationList').data('current', page)
                        getShortLinks(page)
                    }
                });

                $('#paginationPrev').click(function(e){
                    e.preventDefault();
                    let prev_page = $('#paginationList').data('current') - 1
                    getShortLinks(prev_page)
                    $('#paginationList').data('current', prev_page)
                });

                $('#paginationNext').click(function(e){
                    e.preventDefault();
                    let next_page = $('#paginationList').data('current') + 1
                    getShortLinks(next_page)
                    $('#paginationList').data('current', next_page)
                });

                jQuery('#formSubmit').click(function(e){
                    e.preventDefault();
                    
                    if(!$('#link').val()==''){
                        let url = '/graphql?query=mutation{createShortLink(slug: "?slug", link: "?link", status_code: "?status_code"){id,slug,link,status_code}}'

                        url = url.replace('?slug', $('#slug').val())
                        url = url.replace('?link', $('#link').val())
                        url = url.replace('?status_code', $('#status_code').val())

                        jQuery.ajax({
                            url: url,
                            method: 'get',
                            success: function(result){
                                $('#slug').val('')
                                $('#link').val('')
                                $('#link').removeClass('is-invalid')
                                $('#status_code').val('')
                                getShortLinks()
                            }
                        });
                    }
                    else{
                        $('#link').addClass('is-invalid')
                    }
                });

                jQuery('#filterSubmit').click(function(e){
                    e.preventDefault();
                    $('#shortLinksBody').data('filter', $('#filter').val())
                    getShortLinks(1, $('#filter').val())
                });

                jQuery('#getOneSubmit').click(function(e){
                    e.preventDefault();
                    getShortLink($('#getOneId').val(), $('#getOneSlug').val())
                });

                function getShortLink(id = '', slug = ''){
                    let getShortLinkUrl = '/graphql?query={short_link?args{id,slug,link,status_code}}'
                    
                    let queryTextId = id == '' ? '' : 'id: ' + id
                    let queryTextSlug = slug == '' ? '' : 'slug: "' + slug + '"'

                    let queryText = ''

                    if(queryTextId != '' || queryTextSlug != ''){
                        queryText = '(' + queryTextId
                        queryText += queryTextSlug == '' ? ')' : (queryTextId != '' ? ',' : '') + queryTextSlug + ')'
                    }

                    getShortLinkUrl = getShortLinkUrl.replace('?args', queryText)

                    $('#shortLinkBody').html('')

                    jQuery.ajax({
                        url: getShortLinkUrl,
                        method: 'get',
                        success: function(result){
                            $('#getOneId').val('')
                            $('#getOneSlug').val('')
                            let shortLink = result.data.short_link

                            if(shortLink){
                                makeTableRow(shortLink['id'],
                                shortLink['link'],
                                shortLink['slug'],
                                shortLink['status_code'],
                                'shortLinkBody')
                            }
                        }
                    });
                }
            });

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
                                makeRedirectLogRow(i+1,
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
                
                function makeRedirectLogRow(id, created_at, ip_address, browser, link, slug, total, appendTo){
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
