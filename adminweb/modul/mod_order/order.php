<?php
session_start();
 if (empty($_SESSION['username']) AND empty($_SESSION['passuser'])){
  echo "<link href='style.css' rel='stylesheet' type='text/css'>
 <center>Untuk mengakses modul, Anda harus login <br>";
  echo "<a href=../../index.php><b>LOGIN</b></a></center>";
}
else{
    $aksi="modul/mod_order/aksi_order.php";
    switch($_GET['act']){
            // Tampil Order
        default:
            // $kemarin = date('Y-m-d', mktime(0,0,0, date('m'), date('d') - 1, date('Y')));
            // // Update untuk menambah stok 
            // mysql_query("UPDATE produk,orders_detail,orders SET produk.stok=produk.stok+orders_detail.jumlah 
            // WHERE produk.id_produk=orders_detail.id_produk 
            // AND orders.id_orders=orders_detail.id_orders
            // AND orders.tgl_order < '$kemarin' 
            // AND orders.status_order='Belum Dibayar'
            // ");
            // mysql_query("DELETE FROM orders 
            // WHERE status_order='Baru'
            // AND tgl_order < '$kemarin'");

            // $tampil = mysql_query("SELECT * FROM orders,kustomer WHERE orders.id_kustomer=kustomer.id_kustomer ORDER BY status_order ASC ");					
            // while($r=mysql_fetch_array($tampil)){
            //     $tanggal=tgl_indo($r[tgl_order]);

            //     echo "<tr><td align=center>$r[id_orders]</td>
            //     <td>$r[nama]</td>
            //     <td>$tanggal</td>
            //     <td>$r[jam_order]</td>
            //     <td>$r[status_order]</td>
            //     <td><a href=?module=order&act=detailorder&id=$r[id_orders] class='btn btn-warning'>Detail</a></td></tr>";
            //     $no++;
            // }

            $data = [];
            $data['info'] = "";
            if ( $_GET['status_pesanan'] ) {
                if ( $_GET['status_pesanan']=='SEMUA PESANAN' ) {
                    echo 'rows pesanan SEMUA PESANAN';
                }
                if ( $_GET['status_pesanan']=='BELUM BAYAR' ) {
                    $data['sql'] = "SELECT * FROM orders LEFT JOIN kustomer ON orders.id_kustomer=kustomer.id_kustomer WHERE 1 AND orders.status_transaksi='BELUM BAYAR' ORDER BY orders.tgl_order DESC";
                    $data['query'] = mysql_query($data['sql']);
                    while ($value=mysql_fetch_assoc($data['query'])) {
                        $value['tgl_order'] = tgl_indo($value['tgl_order']);
                        $data['rows'][] = "
                            <tr>
                                <td>{$value['id_orders']}</td>
                                <td>{$value['nama']}</td>
                                <td>{$value['tgl_order']}</td>
                                <td>{$value['status_order']}</td>
                                <td>{$value['status_transaksi']}</td>
                                <td>
                                    <a href='?module=order&act=detailorder&id={$value['id_orders']}' class='btn btn-warning'>Detail</a>
                                </td>
                            </tr>
                        ";
                    }
                    $data['rows'] = implode('',$data['rows']);
                }
                if ( $_GET['status_pesanan']=='SEDANG DIPROSES' ) {
                    $data['info'] = "
                        <div class='panel-body' style='border:1px solid #ddd'>
                            # Jika status pembayaran <b>PAID</b>, harap melakukan pengemasan dan pengiriman paket kepada jasa pengiriman yang sudah dipilih pembeli untuk mendapatkan <b>NO RESI</b>.<br>
                            # setelah mendapatkan <b>No RESI</b> harap melakukan <b>INPUT RESI</b> di FORM berikut ini: <br>
                            <form action='?module=order&act=inputresi' method='post'>
                                <div class='input-group' style='margin-bottom:4px'>
                                    <span class='input-group-addon'>No order</span>
                                    <input type='text' class='form-control' name='id_orders' placeholder='Masukan no order disini...' required>
                                </div>
                                <div class='input-group'>
                                    <span class='input-group-addon'>No Resi</span>
                                    <input type='text' class='form-control' name='no_resi' placeholder='Masukan no resi disini...' required>
                                </div>
                                <button type='submit' class='btn btn-default' style='margin-top:4px'>Simpan</button>
                            </form>
                        </div>
                        <hr>
                    ";
                    $data['sql'] = "SELECT * FROM orders LEFT JOIN kustomer ON orders.id_kustomer=kustomer.id_kustomer WHERE 1 AND orders.status_transaksi='SEDANG DIPROSES' ORDER BY orders.tgl_order DESC";
                    $data['query'] = mysql_query($data['sql']);
                    while ($value=mysql_fetch_assoc($data['query'])) {
                        $value['tgl_order'] = tgl_indo($value['tgl_order']);
                        $data['rows'][] = "
                            <tr>
                                <td>{$value['id_orders']}</td>
                                <td>{$value['nama']}</td>
                                <td>{$value['tgl_order']}</td>
                                <td>{$value['status_order']}</td>
                                <td>{$value['status_transaksi']}</td>
                                <td>
                                    <a href='?module=order&act=detailorder&id={$value['id_orders']}' class='btn btn-warning'>Detail</a>
                                </td>
                            </tr>
                        ";
                    }
                    $data['rows'] = implode('',$data['rows']);
                }
                if ( $_GET['status_pesanan']=='SEDANG DIKIRIM' ) {
                    $data['info'] = "
                        <div class='panel panel-default' style='margin-top:20px'>
                            <div class='panel-body'>
                                Untuk melacak pesanan silahkan lakukan seperti langkah berikut ini:<br>
                                1. copy nomor resi pada kolom Status Pesanan No Resi dibawah ini.<br>
                                2. klik jasa pengiriman sesuai dengan kolom Kurir.<br>
                                <a href='https://www.posindonesia.co.id/id/tracking' class='badge btn btn-sm' target='_blank'>Lacak POS</a>
                                <a href='https://www.tiki.id/id/tracking' class='badge btn btn-sm' target='_blank'>Lacak TIKI</a>
                                <a href='https://cekresi.com/' class='badge btn btn-sm' target='_blank'>Lacak JNE</a><br>
                                3. jika pesanan sudah sampai mohon untuk konfirmasi dengan cara memilih menu Pesanan Diterima pada kolom aksi di bawah ini.
                            </div>
                        </div>
                        <hr>
                    ";
                    $data['sql'] = "SELECT * FROM orders LEFT JOIN kustomer ON orders.id_kustomer=kustomer.id_kustomer WHERE 1 AND orders.status_transaksi='SEDANG DIKIRIM' ORDER BY orders.tgl_order DESC";
                    $data['query'] = mysql_query($data['sql']);
                    while ($value=mysql_fetch_assoc($data['query'])) {
                        $value['tgl_order'] = tgl_indo($value['tgl_order']);
                        $data['rows'][] = "
                            <tr>
                                <td>{$value['id_orders']}</td>
                                <td>{$value['nama']}</td>
                                <td>{$value['tgl_order']}</td>
                                <td>{$value['status_order']}</td>
                                <td>{$value['status_transaksi']} dengan No Resi : {$value['no_resi']}</td>
                                <td>
                                    <a href='?module=order&act=detailorder&id={$value['id_orders']}' class='btn btn-warning'>Pesanan Diterima</a>
                                    <a href='?module=order&act=detailorder&id={$value['id_orders']}' class='btn btn-warning'>Detail</a>
                                </td>
                            </tr>
                        ";
                    }
                    $data['rows'] = implode('',$data['rows']);
                }
                if ( $_GET['status_pesanan']=='PESANAN SELESAI' ) {
                    echo 'rows pesanan PESANAN SELESAI';
                }
                if ( $_GET['status_pesanan']=='PESANAN DIBATALKAN' ) {
                    $data['sql'] = "SELECT * FROM orders LEFT JOIN kustomer ON orders.id_kustomer=kustomer.id_kustomer WHERE 1 AND orders.status_transaksi='PESANAN DIBATALKAN' ORDER BY orders.tgl_order DESC";
                    $data['query'] = mysql_query($data['sql']);
                    while ($value=mysql_fetch_assoc($data['query'])) {
                        $value['tgl_order'] = tgl_indo($value['tgl_order']);
                        $data['rows'][] = "
                            <tr>
                                <td>{$value['id_orders']}</td>
                                <td>{$value['nama']}</td>
                                <td>{$value['tgl_order']}</td>
                                <td>{$value['status_order']}</td>
                                <td>{$value['status_transaksi']}</td>
                                <td>
                                    <a href='?module=order&act=detailorder&id={$value['id_orders']}' class='btn btn-warning'>Detail</a>
                                </td>
                            </tr>
                        ";
                    }
                    $data['rows'] = implode('',$data['rows']);
                }
            } else {
                $data['sql'] = "SELECT * FROM orders LEFT JOIN kustomer ON orders.id_kustomer=kustomer.id_kustomer WHERE 1 ORDER BY orders.tgl_order DESC";
                $data['query'] = mysql_query($data['sql']);
                while ($value=mysql_fetch_assoc($data['query'])) {
                    $value['tgl_order'] = tgl_indo($value['tgl_order']);
                    $data['rows'][] = "
                        <tr>
                            <td>{$value['id_orders']}</td>
                            <td>{$value['nama']}</td>
                            <td>{$value['tgl_order']}</td>
                            <td>{$value['status_order']}</td>
                            <td>{$value['status_transaksi']}</td>
                            <td>
                                <a href='?module=order&act=detailorder&id={$value['id_orders']}' class='btn btn-warning'>Detail</a>
                            </td>
                        </tr>
                    ";
                }
                $data['rows'] = implode('',$data['rows']); 
            }

            // echo '<pre>';
            // print_r($data);
            // echo '</pre>';

            echo "
                <div class='col-xs-12'>
                    <div class='box'>
                        <div class='box-header'>
                            <h3 class='box-title'><b>Order</b></h3>
                        </div>
                        <!-- /.box-header -->
                        <div class='box-body'>
                            <hr>
                            <form action='' method='GET'>
                                <input type='hidden' name='module' value='order'>
                                <div class='input-group'>
                                    <span class='input-group-addon' id='basic-addon3'>Filter Status Pesanan</span>
                                    <select name='status_pesanan' class='form-control' onchange='this.form.submit()'>
                                        <option value='SEMUA PESANAN'> SEMUA PESANAN </option>
                                        <option value='BELUM BAYAR'>BELUM BAYAR</option>
                                        <option value='SEDANG DIPROSES'>SEDANG DIPROSES</option>
                                        <option value='SEDANG DIKIRIM'>SEDANG DIKIRIM</option>
                                        <option value='PESANAN SELESAI'>PESANAN SELESAI</option>
                                        <option value='PESANAN DIBATALKAN'>PESANAN DIBATALKAN</option>
                                    </select>
                                </div>
                            </form>
                            <hr>
                            {$data['info']}
                            <table id='example1' class='table table-bordered table-striped'> 
                                <thead>
                                    <tr>
                                        <th>no.order</th>
                                        <th>nama konsumen</th>
                                        <th>tgl. order</th>
                                        <th>status pembayaran</th>
                                        <th>status pesanan</th>
                                        <th>aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {$data['rows']}
                                </tbody>
                            </table>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
            ";

        
        break;
        
        
    case "inputresi":
            $update = mysql_query("UPDATE orders SET status_transaksi='SEDANG DIKIRIM',no_resi='{$_POST['no_resi']}' where id_orders='{$_POST['id_orders']}'");
            if ( $update ) {
                $alert = "<script>window.alert('No Resi berhasil disimpan');window.location=('media.php?module=order&status_pesanan=SEDANG_DIKIRIM')</script>";
            } else {
                $alert = "<script>window.alert('No Resi gagal disimpan, pastikan id order sudah benar');window.history.go(-1)</script>";
            }
            echo $alert;
        break;

    case "detailorder":
    
$edit = mysql_query("SELECT * FROM orders WHERE id_orders='$_GET[id]'");
    $r    = mysql_fetch_array($edit);
    $tanggal=tgl_indo($r['tgl_order']);
	$customer=mysql_query("select * from kustomer where id_kustomer='$r[id_kustomer]'");
  $c=mysql_fetch_array($customer);
    $pilihan_status = array('Belum-Dibayar','Sudah-Dibayar','Sudah-Dikirim');
    $pilihan_order = '';
    foreach ($pilihan_status as $status) {
	   $pilihan_order .= "<option value=$status";
	   if ($status == $r['status_order']) {
		    $pilihan_order .= " selected";
	   }
	   $pilihan_order .= ">$status</option>\r\n";
    }		
echo"

        <div class='box'>
            <div class='box-header'>
              <h3 class='box-title'><b>Detail Order</b></h3>
            </div>
            <!-- /.box-header -->
            <div class='box-body'>
      <!-- info row -->
      <div class='row invoice-info'>
        
        <div class='col-sm-4 invoice-col'>
          Kepada
          <address>
            <strong>$c[nama]</strong><br>
            $r[alamat]<br>
            Phone: $c[no_telp]<br>
            Email: $c[email]
          </address>
        </div>
        <!-- /.col -->
		<form method=POST action=$aksi?module=order&act=update>
          <input type=hidden name=id value=$r[id_orders]>
          <input type=hidden name=status_order_lama value='$r[status_order]'>
        <div class='col-sm-4 invoice-col'>
          <b>Invoice</b><br>
          <br>
          <b>Order ID:</b> $r[id_orders]<br>
          <b>Tgl. Transaksi:</b> $tanggal<br>
          <b>Metode Pembayaran:</b> $r[jenis_pembayaran]<br>
		  <b>Kurir:</b> $r[kurir]<br>
		  <b>Paket Kurir:</b> $r[paket]<br>
		  <b>Status:</b>  <select name=status_order>$pilihan_order</select> 
          <input type=submit value='Ubah Status'>
        </div>
		</form>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <!-- Table row -->
      <div class='row'>
        <div class='col-xs-12 table-responsive'>
          <table class='table table-striped'>
            <thead>
            <tr>
              <th>Produk</th>
              <th>Berat(Kg)</th>
              <!--<th>Ukuran</th>-->
              <th>Jumlah</th>
              <th>Harga</th>
			  <th>Subtotal</th>
            </tr>
            </thead>";
			// tampilkan rincian produk yang di order
  $sql2=mysql_query("SELECT * FROM orders_detail a left join produk b on a.id_produk=b.id_produk
		left join ukuran c on a.id_ukuran=c.id_ukuran WHERE id_orders='$_GET[id]'");
		 while($s=mysql_fetch_array($sql2)){
  $subtotalberat = $s[berat] * $s[jumlah]; // total berat per item produk 
   $totalberat  = $totalberat + $subtotalberat; // grand total berat all produk yang dibeli

    $harga1 = $s[harga];
	
   
   $subtotal    = $harga1 * $s[jumlah];
   $total       = $total + $subtotal;
   $subtotal_rp = format_rupiah($subtotal);    
   $total_rp    = format_rupiah($total);    
   $harga       = format_rupiah($harga1);
		echo"
            <tbody>
            <tr>
              <td>$s[nama_produk]</td>
              <td>$s[berat]</td>
              <!--<td>$s[kode_ukuran]</td>-->
              <td>$s[jumlah]</td>
              <td>Rp. $harga</td>
			  <td>Rp. $subtotal_rp</td>
            </tr>
			</tbody>";
			}
  
  $ongkoskirim1=$r[ongkir];
  $ongkoskirim=$ongkoskirim1 * $totalberat;
	
  $grandtotal    = $total + $ongkoskirim; 

  $ongkoskirim_rp = format_rupiah($ongkoskirim);
  $ongkoskirim1_rp = format_rupiah($ongkoskirim1); 
  $grandtotal_rp  = format_rupiah($grandtotal); 
			echo"
          </table>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <div class='row'>
        <!-- accepted payments column -->
        <div class='col-xs-6'>
          
        </div>
        <!-- /.col -->
        <div class='col-xs-6'>
          

          <div class='table-responsive'>
            <table class='table'>
              <tr>
                <th style='width:60%'>Total:</th>
                <td>Rp. $total_rp</td>
              </tr>
              <tr>
                <th>Ongkos Kirim:</th>
                <td>Rp. $ongkoskirim1_rp/Kg</td>
              </tr>
              <tr>
                <th>Total Berat:</th>
                <td>$totalberat Kg</td>
              </tr>
              <tr>
                <th>Total Ongkos Kirim:</th>
                <td>Rp. $ongkoskirim_rp</td>
              </tr>
			  <tr>
                <th>Grand Total:</th>
                <td>Rp. $grandtotal_rp</td>
              </tr>
            </table>
          </div>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <!-- this row will not appear when printing -->
      <div class='row no-print'>
        <div class='col-xs-12'>
          
         
			<a href=modul/mod_order/cetak.php?id=$r[id_orders] target='_blank' class='btn btn-default'><i class='fa fa-print'></i> Print</a><br>
        </div>
      </div>
	  
	  
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>";
    break;  
}
}
?>
