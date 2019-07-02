<?php
foreach ($data as $val) {
    $btn = '<a title="Set jawaban yang benar (Pilihan Ganda)" data-toggle="tooltip" href="javascript:;" onclick="set_jawaban(this);return false" pg-id="'.$val->pg_id.'" soal-id="'.$val->soal_id.'" class="jawab_'.$val->pg_id.' btn btn-xs btn-default btn-flat"><i class="fa fa-check"></i></a>';
    if ($val->pg_is_right == 1){
        $btn = '<a title="Set jawaban yang benar (Pilihan Ganda)" data-toggle="tooltip" href="javascript:;" onclick="set_jawaban(this);return false" pg-id="'.$val->pg_id.'" soal-id="'.$val->soal_id.'" class="jawab_'.$val->pg_id.' hidden btn btn-xs btn-default btn-flat"><i class="fa fa-check"></i></a>';
    }
    echo '<div class="pgcontent">
            <strong class="pgnomor">'.$this->conv->toStr($val->pg_nomor).'</strong>
            <div class="pg_'.$val->pg_id.'">'.$val->pg_content.'</div>
            <div style="min-height:30px">
                <div class="toolbar">
                    '.$btn.'
                    <a data-toggle="tooltip" href="'.base_url('mapel/edit_pg/'.$val->pg_id).'" onclick="show_modal(this);return false" class="btn btn-xs btn-primary btn-flat" title="Edit Jawaban"><i class="fa fa-pencil"></i></a>
                    <a data-toggle="tooltip" href="javascript:;" onclick="delete_pg(this);return false" data-id="'.$val->pg_id.'" class="btn btn-xs btn-danger btn-flat" title="Hapus Jawaban"><i class="fa fa-trash"></i></a>
                    <a data-toggle="tooltip" title="Skor" href="javascript:;" class="btn btn-xs btn-default btn-flat score_'.$val->pg_id.'">'.$val->pg_score.'</a>
                </div>
            </div>
          </div>';
}
?>