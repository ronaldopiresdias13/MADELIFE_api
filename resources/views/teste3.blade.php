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

            <p style="page-break-after: always;">
                <h1>Content Page 1</h1>
                {{-- @for ($i = 1; $i < 20; $i++)
                    <i style="background-color: cadetblue">Teste {{ $i }}</i>
                    <br>
                @endfor --}}
            </p>
            <p style="page-break-after: never;">
                <h1>Content Page 2</h1>
                {{-- @for ($i = 1; $i < 20; $i++)
                    <i style="background-color: cadetblue">Teste {{ $i }}</i>
                    <br>
                @endfor --}}
            </p>
        </main>
    </body>
</html>
