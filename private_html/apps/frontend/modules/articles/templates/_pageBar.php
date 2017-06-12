<div class="page_nr">
    <?php if($pager->pageCount() > 1): ?>

        <?php if(!$pager->isFirstPage($current_page)): ?>
            <?php echo link_to('<< ', $pageUrl . '1' ) ?>
            <?php echo link_to('< ', $pageUrl . ($current_page - 1)) ?>
        <?php endif; ?>

        <?php for($i = 1, $pc = $pager->pageCount(); $i <= $pc; $i++): ?>
            <?php if($i == $current_page): ?>
                <?php echo link_to(strval($i), $pageUrl . $i, array('class' => 'selected')) ?>
            <?php else: ?>
                <?php echo link_to(strval($i), $pageUrl . $i) ?>
            <?php endif; ?>
        <?php endfor; ?>

        <?php if(!$pager->isLastPage($current_page)): ?>
            <?php echo link_to(' >', '/articles/latest/' . ($current_page + 1)) ?>
            <?php echo link_to(' >>', '/articles/latest/' . $pager->pageCount()) ?>
        <?php endif; ?>

    <?php endif; ?>
</div>