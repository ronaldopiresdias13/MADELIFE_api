<html>
    <head>
        <style>
            /** Define the margins of your page **/
            @page {
                margin: 40px 0 20px 0;
            }

            header {
                position: fixed;
                top: -40px;
                left: 0px;
                right: 0px;
                height: 40px;

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
                line-height: 20px;
            }
        </style>
    </head>
    <body>
        <!-- Define header and footer blocks before your content -->
        <header>
            Our Code World
        </header>

        <footer>
            Copyright &copy; <?php echo date("Y");?>
        </footer>

        <!-- Wrap the content of your PDF inside a main tag -->
        <main style="background-color: red">
            @for ($i = 1; $i < 120; $i++)
                <i style="background-color: cadetblue">Teste {{ $i }}</i>
                <br>
            @endfor
            {{-- <p style="page-break-after: always;">
                Content Page 1
            </p>
            <p style="page-break-after: never;">
                Content Page 2
            </p> --}}
        </main>
    </body>
</html>
