<html>
    <head>
        <style>
            /** Define the margins of your page **/
            @page {
                margin: 100px 0 20px 0;
            }

            header {
                position: fixed;
                top: -100px;
                left: 0px;
                right: 0px;
                height: 100px;

                /** Extra personal styles **/
                background-color: #03a9f4;
                color: white;
                text-align: center;
                line-height: 30px;
            }

            footer {
                position: fixed;
                bottom: -20px;
                left: 0px;
                right: 0px;
                height: 20px;

                /** Extra personal styles **/
                background-color: #03a9f4;
                color: white;
                text-align: center;
                /* line-height: 20px; */
            }

            footer span:after {
                content: counter(page);
            }

            footer span {
                float: right;
                margin-right: 10px;
            }

        </style>
    </head>
    <body>
        <!-- Define header and footer blocks before your content -->
        <header>
            Our Code World
        </header>

        <footer>
            <?=date("d/m/Y");?>
            <span>Página </span>
        </footer>

        <!-- Wrap the content of your PDF inside a main tag -->
        <main>
            @for ($i = 1; $i < 100; $i++)
                <i>Teste {{ $i }}</i>
                <br>
            @endfor
            <p style="page-break-after: always;"></p>
            @for ($i = 1; $i < 20; $i++)
                <i>Teste {{ $i }}</i>
                <br>
            @endfor
            {{-- <p style="page-break-after: never;"></p> --}}
            {{-- <p style="page-break-after: always;"></p> --}}
        </main>
    </body>
</html>
