<figure class="figure">
    <?php if ($row['image']) { ?>
        <div class="ratio ratio-16x9">
            <img src="data:image/jpeg;base64, <?php echo base64_encode($row['image']) ?>" alt=""
                class="figure-img object-fit-cover ratio ratio-16x9">
        </div>
    <?php } ?>
    <figcaption class="figure-caption">
        <strong>
            <?php echo $row['title']; ?>
        </strong>
        <br>
        <?php echo $row['date_modified'] == null ? $row['date_added'] : $row['date_modified'] . '*'; ?>
    </figcaption>
</figure>