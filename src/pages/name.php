
	<h1>Hello <?= html($username) ?></h1>
    <?= html('<script>alert("laminas")</script>') ?>

    <form action="/checkpost" method="POST">
        <input type="text" name="something" placeholder="something" />
        <input type="hidden" name="<?= $nameKey ?>" value="<?= $name ?>">
        <input type="hidden" name="<?= $valueKey ?>" value="<?= $value ?>">
        <button type="submit">Submit Me</button>
    </form>

    <div id="swapme"></div>
    <button hx-get="/testme" hx-trigger="click" hx-target="#swapme" hx-swap="innerHTML">Click Me</button>