<?php
/* @var $sampleUrl string */
/* @var $model common\excel\ExcelUploadForm */
?>

<div class="card">
    <h5 class="card-header">Upload Guide</h5>
    <div class="card-body">
        <div class="text-muted">
            <p>
                1. You can upload an <b>excel</b> file or <b>csv</b>
            </p>

            <p>
                2. Set the excel sheet from where to export the data. The default is the first sheet.
            </p>

            <p>
                3. Go to the Advanced Excel Options to set the following:
            <ul>
                <li>
                    Set the Row to start reading data from. If first row (Row 1) has column names, then set to row 2 to
                    skip row
                    1 with the column names
                </li>
                <li>
                    Set the column range to read. By default this has been set from Column A to Column J (10 columns).
                    Usually
                    this will work unless you have many columns you are exporting in which case you can increase the
                    range from
                    A to J to A to Z
                </li>
            </ul>
            </p>
            <p>
                4. Upload Excel/CSV with these columns:
                <strong>
                    <?= implode(', ', $model->targetModel->getExcelColumns()) ?>
                </strong>
            </p>

            <p>
                5. Click here to <a href="<?= $sampleUrl ?>" target="_top">download a sample excel.</a>
            </p>
        </div>
    </div>
</div>