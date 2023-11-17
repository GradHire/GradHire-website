<?php

$currentTab = $_COOKIE['currentTab'] ?? 'tab1';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['tab'])) {
        $currentTab = $_POST['tab'];
        setcookie('currentTab', $currentTab, time() + (86400 * 30), '/');
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit();
    }
}

?>

<form method="POST" action="" class="relative text-[14px] w-full m-0 bg-zinc-50 isolate justify-around flex gap-2 flex-row overflow-hidden h-14 border rounded-2xl text-[#1A2421] backdrop-blur-xl p-2 [ border-[1px] border-solid border-black  border-opacity-10 ] [ shadow-black/5 shadow-2xl ]">
    <button type="submit" name="tab" value="tab1"
            class="py-2 px-4 rounded-[8px] w-full <?php if ($currentTab === 'tab1') echo 'bg-white text-black shadow-zinc-500/5 shadow-md font-semibold'; else echo ' text-zinc-400'; ?>">
        Statistiques
    </button>
    <button type="submit" name="tab" value="tab2"
            class="py-2 px-4 rounded-[8px] w-full <?php if ($currentTab === 'tab2') echo 'bg-white text-black shadow-zinc-500/5 shadow-md font-semibold'; else echo ' text-zinc-400'; ?>">
        Actions
    </button>
    <button type="submit" name="tab" value="tab3"
            class="py-2 px-4 rounded-[8px] w-full <?php if ($currentTab === 'tab3') echo 'bg-white text-black shadow-zinc-500/5 shadow-md font-semibold'; else echo ' text-zinc-400'; ?>">
        Favoris
    </button>
</form>
