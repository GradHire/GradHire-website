<?php

// Check if a tab is set in the session, otherwise set a default tab
$currentTab = isset($_SESSION['currentTab']) ? $_SESSION['currentTab'] : 'tab1';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update the current tab in the session when a tab button is clicked
    if (isset($_POST['tab'])) {
        $_SESSION['currentTab'] = $_POST['tab'];
        $currentTab = $_SESSION['currentTab'];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tabbed Page</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.15/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
<div class="container mx-auto">
    <h2 class="text-2xl font-bold mt-8">Tabbed Page</h2>
    <div class="flex mt-8">
        <form method="POST" action="">
            <button type="submit" name="tab" value="tab1" class="py-2 px-4 rounded-l <?php if ($currentTab === 'tab1') echo 'bg-blue-500 text-white'; else echo 'bg-gray-200 text-gray-800'; ?>">Tab 1</button>
            <button type="submit" name="tab" value="tab2" class="py-2 px-4 <?php if ($currentTab === 'tab2') echo 'bg-blue-500 text-white'; else echo 'bg-gray-200 text-gray-800'; ?>">Tab 2</button>
            <button type="submit" name="tab" value="tab3" class="py-2 px-4 rounded-r <?php if ($currentTab === 'tab3') echo 'bg-blue-500 text-white'; else echo 'bg-gray-200 text-gray-800'; ?>">Tab 3</button>
        </form>
    </div>
    <div class="mt-8">
        <div id="tab1" class="<?php if ($currentTab === 'tab1') echo 'block'; else echo 'hidden'; ?>">
            <h3 class="text-xl font-bold">Tab 1 content</h3>
            <p>This is the content for Tab 1.</p>
        </div>
        <div id="tab2" class="<?php if ($currentTab === 'tab2') echo 'block'; else echo 'hidden'; ?>">
            <h3 class="text-xl font-bold">Tab 2 content</h3>
            <p>This is the content for Tab 2.</p>
        </div>
        <div id="tab3" class="<?php if ($currentTab === 'tab3') echo 'block'; else echo 'hidden'; ?>">
            <h3 class="text-xl font-bold">Tab 3 content</h3>
            <p>This is the content for Tab 3.</p>
        </div>
    </div>
</div>
</body>
</html>
