<?php
/* @var $this yii\web\View */
/* @var $country_id int */
?>
<div id="queryOptions" class="hidden">
    <h3>Query Options</h3>
    <div class="row row-no-gutters mt-3">
        <div class="col-md-8"><label for="limit">Limit: </label></div>
    </div>
    <div class="row row-no-gutters mb-2">
        <div class="col-md-10">
            <input name="limit" id="limit" type="number" value="" class="form-control form-control-sm"/>
        </div>
    </div>
    <div class="row row-no-gutters mt-2">
        <div class="col-md-3"><label for="orderby">Order By: </label></div>
    </div>
    <div class="row row-no-gutters mt-2">
        <div class="col-md-10">
            <select name="orderby" id="orderby" type="text" class="form-control form-control-sm"></select>
        </div>
    </div>
    <div class="mt-4">
        <button id="askForName" role="button" class="btn btn-primary col-md-10" data-toggle="modal" data-target="#inputName">Generate & Save Report</button>
    </div>
    <div class="mt-2">
        <button id="generateQuery" role="button" class="btn btn-generatequery col-md-10">Preview Query</button>
    </div>

    <div class="row card card-body mt-4 mb-4 col-md-12 hidden"
         id="previewQueryCard">
        <div class="bd-clipboard">
            <button type="button" data-clipboard-target="#queryHolder" class="btn-clipboard">Copy</button>
        </div>
        <figure class="highlight hidden">
            <pre class="pre-scrollable">
                <code id="" class="language-sql text-wrap word-wrap" data-lang="sql"></code>
            </pre>
        </figure>
        <textarea id="queryHolder" class="language-sql text-wrap word-wrap" style="width: 100%"></textarea>
    </div>

    <div id="inputName" role="dialog" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <!--Modal header-->
                <div class="modal-header mt-2">
                    <h4 class="modal-title">Save Generated Report: </h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">Close</span>
                    </button>
                </div>
                <!--Modal Body-->
                <div class="modal-body">
                    <div id="reportNameMessage"></div>
                    <div class="form-group required">
                        <div class="row row-no-gutters mt-2">
                            <div class="col-md-10 mx-auto">
                                <label for="name" class="control-group">Report Name</label>
                            </div>
                        </div>
                        <div class="row row-no-gutters mt-2">
                            <div class="col-md-10 mx-auto">
                                <input name="name" id="name" type="text" value="" class="form-control form-control-sm"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="saveReport" role="button" class="btn btn-success col-md-10 mx-auto">Generate & Save Report</button>
                </div>
            </div>
        </div>
    </div>
</div>
