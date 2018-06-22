<?php
if(!empty($data_display)){

    $i = $prev_stt;

    foreach($data_display as $item){
        $i++;
    ?>
    <tr site_id="<?php echo $item['id']?>" stt="<?php echo $i;?>">
        <td class="t_left" style="padding-left: 1%;"><?php echo $i;?> . <?php echo $item['site_name']?></td>
        <td widtd="10%" class="t_center">
        <?php
            echo "¥".number_format($item['transactionRevenue']);
        ?>
        </td>
        <td widtd="10%" class="t_center">
        <?php
          echo "¥".number_format($item['transactionRevenue_predict']);
        ?>
        </td>
        <td widtd="10%" class="t_center"><?php echo ($item['rate'] != 0 ? number_format($item['rate'],1) : "0")."%"?></td>
        <td widtd="10%" class="t_center"><?php echo number_format($item['uniqueUsers']);?></td>
        <td widtd="10%" class="t_center"><?php echo number_format($item['pageviews']);?></td>
        <td widtd="10%" class="t_center">
        <?php
          echo $this->Shishimai->displayActualKPIpageV2('bounceRate',$item,'number','','%',1);
        ?>
        </td>
        <td widtd="10%" class="t_center">
        <?php
            echo $this->Shishimai->displayActualKPIpageV2('transactions',$item,'number','','',0);
        ?>
        </td>
        <td widtd="10%" class="t_center">
        <?php
            $text = $this->Shishimai->displayActualKPIpageV2('revenuePerTransaction',$item,'number','¥','',0);
            if($text != '-'){
                echo '¥'.str_replace('¥','',$text);
            }else{
                echo '-';
            }
        ?>
        </td>
    </tr>
    <?php
    }
}else{
    ?>
    <tr site_id="empty_data"><td colspan="9" style="text-align"> Empty data </td></tr>
    <?php
}
?>

