<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> GradHire | <?= $this->title ?></title>
    <link rel="stylesheet" href="/resources/css/input.css">
    <link rel="stylesheet" href="/resources/css/output.css">
    <link rel="icon" type="image/png" sizes="32x32" href="/resources/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/resources/images/favicon-16x16.png">
</head>
<body class="font-sans">

<nav aria-label="Top"
     class="fixed z-20 w-full border-b border-gray-200">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 top-0 z-50">
        <div class="flex h-16 gap-4 items-center">
            <div class="flex lg:ml-0">
                <a href="/">
                    <span class="sr-only">GradHire</span>
                    <img class="h-8 w-auto" src="/resources/images/logo.png" alt="">
                </a>
            </div>

            <div class="ml-auto flex items-center">
                <div class="flex flex-1 lg:items-center justify-end space-x-6">
                    <a href="/login" class="text-sm font-medium text-zinc-700 hover:text-zinc-800">Se
                        connecter</a>
                    <span class="h-6 w-px bg-zinc-400" aria-hidden="true"></span>
                    <a href="/register"
                       class="text-sm font-medium text-zinc-700 hover:text-zinc-800">S'inscrire</a>
                </div>
            </div>
        </div>
    </div>
</nav>
<div id="blur-background" class="hidden w-screen h-screen fixed z-50 top-0 left-0 backdrop-blur-md"></div>
<div class="w-full flex  justify-center items-center flex-col">
    <div class="w-full flex flex-col justify-center items-center py-0 max-w-7xl mt-[65px] px-4 sm:px-6 lg:px-8">
        {{content}}
        <footer aria-labelledby="footer-heading" class="bg-white w-full">
            <h2 id="footer-heading" class="sr-only">Footer</h2>
            <div class="mx-auto max-w-7xl ">
                <div class="border-t border-zinc-200 py-10">
                    <p class="text-sm text-zinc-500">Copyright &copy; 2023 -
                        <span class="text-zinc-900">GradHire</span>
                    </p>
                </div>
            </div>
        </footer>
    </div>
</div>
</body>
</html>