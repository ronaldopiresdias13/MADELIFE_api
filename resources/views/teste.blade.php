<html>
    <head>
        <style>
            /**
                Set the margins of the page to 0, so the footer and the header
                can be of the full height and width !
             **/
            @page {
                margin: 0cm 0cm;
            }

            /** Define now the real margins of every page in the PDF **/
            body {
                margin-top: 2cm;
                margin-left: 1cm;
                margin-right: 1cm;
                /* margin-bottom: 2cm; */
                background-color: red;
            }

            /** Define the header rules **/
            header {
                position: fixed;
                top: 0cm;
                left: 0cm;
                right: 0cm;
                height: 2cm;

                /** Extra personal styles **/
                background-color: #03a9f4;
                color: white;
                text-align: center;
                line-height: 1.5cm;
            }

            /** Define the footer rules **/
            footer {
                position: fixed;
                bottom: 0cm;
                left: 0cm;
                right: 0cm;
                height: 2cm;

                /** Extra personal styles **/
                background-color: #03a9f4;
                color: white;
                text-align: center;
                line-height: 1.5cm;
            }
        </style>
    </head>
    <body>
        <!-- Define header and footer blocks before your content -->
        <header>
            Our Code World
        </header>

        {{-- <footer>
            Copyright &copy; <?php echo date("Y");?>
        </footer> --}}

        <!-- Wrap the content of your PDF inside a main tag -->
        <main>
            {{-- <h1>Hello World</h1> --}}
            @for ($i = 1; $i < 120; $i++)
                <i style="background-color: cadetblue">Teste {{ $i }}</i>
                <br>
            @endfor
        </main>
    </body>
</html>
