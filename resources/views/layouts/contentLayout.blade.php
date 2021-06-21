<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Laravel</title>
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        {{-- Include core + vendor Styles --}}
        @include('panels/styles')

        {{-- Include page Style --}}
        @yield('mystyle')

    </head>

    <body>
      <!-- START PAGE CONTAINER -->

        <!-- PAGE CONTENT -->
        <div class="page-content pt-3">

          <!-- START BREADCRUMB -->

          <!-- BEGIN: Content-->
          <div class="page-content-wrap">
            <!-- BEGIN: Header-->
            <div class="content-overlay"></div>
              <div class="row">
                <div class="col-md-12">
                  <div class="panel panel-default">
                    {{-- Page Panel Heading --}}
                    @if(!(isset($page) && ($page=='Home')))
                      <div class="panel-heading">
                        <div class="panel-title-box">
                        <h3> @yield('title') </h3>
                        </div>
                        <ul class="panel-controls">
                          {{-- Include Custom Action Menu --}}
                          @yield('action_menu')
                        </ul>
                      </div>
                    @endif
                    {{-- End Page Panel Heading --}}

                    <!-- Dynamic Page Content -->
                    <div class="panel-body">
                      {{-- Include Startkit Content --}}
                      @yield('content')
                      {{-- Include Startkit Content --}}
                    </div>
                    <!-- End Dynamic Page Content  -->

                  </div>

                  {{-- Notification Bar --}}
                  @yield('notify_status')
                  {{-- End notification Bar --}}

                </div>
              </div>
              <!-- End: Content-->

            </div>
          </div>
        </div>

      {{-- include default scripts --}}
      @include('panels/scripts')

      {{-- Include page script --}}
      @yield('myscript')

    </body>
</html>
