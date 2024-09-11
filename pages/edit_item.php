<?php
require_once('../templates/common.tpl.php');
require_once('../database/item.class.php');
require_once('../session/session.php');

$session = new Session();

    $itemData = json_decode($_POST['data'], true);

    $item = new Item(
        $itemData['id'],
        $itemData['itemName'],
        $itemData['itemBrand'],
        $itemData['itemDescription'],
        $itemData['itemPrice'],
        $itemData['itemOwner'],
        $itemData['itemCategory'],
        $itemData['ItemImage'],
        $itemData['itemSize'],
        $itemData['itemCondition']
    );

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Page</title>
    <link rel="stylesheet" href="style/style.css">
    <link rel="icon" href="../imgs/logo.jpg" type="image/x-icon">
    <script src="../javascript/UploadImage.js" defer></script>
</head>

<body>
    <?php drawHeaderForSell($session,false); ?>
    <main class="sell">
        <h1>Edit an Item</h1>

        <div class="img-load">
            <div class="border">
                <svg xmlns="http://www.w3.org/2000/svg" width="70" height="70" fill="#0056B3" class="upload-icon" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M4.406 1.342A5.53 5.53 0 0 1 8 0c2.69 0 4.923 2 5.166 4.579C14.758 4.804 16 6.137 16 7.773 16 9.569 14.502 11 12.687 11H10a.5.5 0 0 1 0-1h2.688C13.979 10 15 8.988 15 7.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 2.825 10.328 1 8 1a4.53 4.53 0 0 0-2.941 1.1c-.757.652-1.153 1.438-1.153 2.055v.448l-.445.049C2.064 4.805 1 5.952 1 7.318 1 8.785 2.23 10 3.781 10H6a.5.5 0 0 1 0 1H3.781C1.708 11 0 9.366 0 7.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383" />
                    <path fill-rule="evenodd" d="M7.646 4.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707V14.5a.5.5 0 0 1-1 0V5.707L5.354 7.854a.5.5 0 1 1-.708-.708z" />
                </svg>
                <p>Drag and Drop files here</p>
                <p>or</p>
                <button onclick="triggerFileInput()">
                    Upload Images
                </button>
            </div>
        </div>
        <br>
        <div class="description">
        <form action="../actions/action_edit_item.php" method="post" enctype="multipart/form-data">
            <input type="file" id="hiddenInput" name="ItemImage" accept=".png,.jpg,.jpeg" multiple>
            <input type="hidden" name="itemId" value="<?php echo $item->id; ?>">

                <div class="title">
                    <span>Title</span>
                    <div class="title-input">
                        <input class="input" type="text" name="ItemName" placeholder="Item Name" value="<?php echo $item->itemName; ?>" required>
                        <label for="title" class="label">Item Name</label>
                    </div>  
                </div>

                <div class="brand">
                    <span>Brand</span>
                    <div class="brand-input">
                        <input class="input" type="text" name="ItemBrand" placeholder="Item Brand" value="<?php echo $item->itemBrand; ?>" required>
                        <label for="brand" class="label">Brand</label>
                    </div>
                </div>

                <div class="owner-input">
                    <input type="hidden" name="ItemOwner" value="<?php echo $session->getName(); ?>">
                </div>
                <div class="descricao">
                    <span>Description</span>
                    <div class="descri-input">
                        <textarea class="input" name="ItemDescription" placeholder="Item Description" required><?php echo $item->itemDescription ?></textarea>
                        <label for="description" class="label">Description</label>
                    </div>
                </div>

        </div>
        <br>
        <div class="category-div">
            <div class="category">
                <span>Category</span>
                <div class="choose-cat">
                    <select name="ItemCategory" class="item-category">
                        <option value="Kids" <?php if($itemCategory === 'Kids') echo 'selected' ?>>Kids</option>
                        <option value="Male" <?php if($itemCategory === 'Male') echo 'selected' ?>>Male</option>
                        <option value="Female" <?php if($itemCategory === 'Female') echo 'selected' ?>>Female</option>
                    </select>
                    <div class="border-select"></div>
                    <div class="icon-select">
                        <i class="fa-solid fa-caret-down"></i>
                    </div>
                </div>
            </div>


            <div class="condition">
                <span>Condition</span>
                <div class="choose-cond">
                    <select name="ItemCondition" class="item-condition">
                        <option value="New with tags" <?php if($itemCondition === 'New with tags') echo 'selected' ?>>New with tags</option>
                        <option value="New without tags" <?php if($itemCondition === 'New without tags') echo 'selected' ?>>New without tags.</option>
                        <option value="Very good" <?php if($itemCondition === 'Very good') echo 'selected' ?>>Very good</option>
                        <option value="Good" <?php if($itemCondition === 'Good') echo 'selected' ?>>Good</option>
                        <option value="Satisfactory" <?php if($itemCondition === 'Satisfactory') echo 'selected' ?>>Satisfactory</option>
                        <option value="Bad" <?php if($itemCondition === 'Bad') echo 'selected' ?>>Bad</option>
                    </select>
                    <div class="border-select"></div>
                    <div class="icon-select">
                        <i class="fa-solid fa-caret-down"></i>
                    </div>
                </div>
            </div>

            <div class="size">
                <span>Size</span>
                <div class="choose-size">
                <select name="ItemSize" class="item-size">
                    <option value="36" <?php if($item->itemSize === '36') echo 'selected' ?>>36</option>
                    <option value="37" <?php if($item->itemSize === '37') echo 'selected' ?>>37</option>
                    <option value="38" <?php if($item->itemSize === '38') echo 'selected' ?>>38</option>
                    <option value="39" <?php if($item->itemSize === '39') echo 'selected' ?>>39</option>
                    <option value="40" <?php if($item->itemSize === '40') echo 'selected' ?>>40</option>
                    <option value="41" <?php if($item->itemSize === '41') echo 'selected' ?>>41</option>
                    <option value="42" <?php if($item->itemSize === '42') echo 'selected' ?>>42</option>
                    <option value="43" <?php if($item->itemSize === '43') echo 'selected' ?>>43</option>
                    <option value="44" <?php if($item->itemSize === '44') echo 'selected' ?>>44</option>
                    <option value="45" <?php if($item->itemSize === '45') echo 'selected' ?>>45</option>
                    <option value="46" <?php if($item->itemSize === '46') echo 'selected' ?>>46</option>
                </select>
                    <div class="border-select"></div>
                    <div class="icon-select">
                        <i class="fa-solid fa-caret-down"></i>
                    </div>
                </div>
            </div>
            <div class="price">
                <span>Price</span>
                <div class="price-input">
                    <input class="input" type="text" name="ItemPrice" placeholder="€ 0,00" value="<?php echo $item->itemPrice ?>" required>
                    <label for="title" class="label">Price</label>
                </div>
            </div>
            <input type="hidden" name="csrf" value="<?= $session->getCSRF() ?>">
            <button class="load-btn">Update Item</button>
            </form>
        </div>
    </main>
</body>

</html>
