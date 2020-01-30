<?php
defined('APLIKASI') or exit('Anda tidak dizinkan mengakses langsung script ini!');
?>
<div class='modal fade' id='tambahjadwal' style='display: none;'>
	<div class='modal-dialog'>
		<div class='modal-content'>
			<div class='modal-header bg-maroon'>
				<button class='close' data-dismiss='modal'><span aria-hidden='true'><i class='glyphicon glyphicon-remove'></i></span></button>
				<h4 class='modal-title'><i class="fas fa-business-time fa-fw"></i> Tambah Jadwal Ujian</h4>
			</div>
			<div class='modal-body'>
				<form id="formtambahujian" method='post'>
					<div class='form-group'>
						<label>Nama Bank Soal</label>
						<select name='idmapel' class='form-control' required='true'>
							<?php
							if ($pengawas['level'] == 'admin') {
								$namamapelx = mysqli_query($koneksi, "SELECT * FROM mapel where status='1' order by nama ASC");
							} else {
								$namamapelx = mysqli_query($koneksi, "SELECT * FROM mapel where status='1' and idguru='$id_pengawas' order by nama ASC");
							}
							while ($namamapel = mysqli_fetch_array($namamapelx)) {
								$dataArray = unserialize($namamapel['kelas']);
								echo "<option value='$namamapel[id_mapel]'>$namamapel[nama] - $namamapel[level] - ";
								foreach ($dataArray as $key => $value) {
									echo "$value ";
								}
								echo "</option>";
							}
							?>
						</select>
					</div>
					<div class='form-group'>
						<label>Nama Jenis Ujian</label>
						<select name='kode_ujian' class='form-control' required='true'>
							<option value=''>Pilih Jenis Ujian </option>
							<?php
							$namaujianx = mysqli_query($koneksi, "SELECT * FROM jenis where status='aktif' order by nama ASC");
							while ($ujian = mysqli_fetch_array($namaujianx)) {
								echo "<option value='$ujian[id_jenis]'>$ujian[id_jenis] - $ujian[nama] </option>";
							}
							?>
						</select>
					</div>
					<div class='form-group'>
						<div class='row'>
							<div class='col-md-6'>
								<label>Tanggal Mulai Ujian</label>
								<input type='text' name='tgl_ujian' class='tgl form-control' autocomplete='off' required='true' />
							</div>
							<div class='col-md-6'>
								<label>Tanggal Waktu Expired</label>
								<input type='text' name='tgl_selesai' class='tgl form-control' autocomplete='off' required='true' />
							</div>
						</div>
					</div>
					<div class='form-group'>
						<label>Sesi</label>
						<select name='sesi' class='form-control' required='true'>
							<?php
							$sesix = mysqli_query($koneksi, "SELECT * from sesi");
							while ($sesi = mysqli_fetch_array($sesix)) {
								echo "<option value='$sesi[kode_sesi]'>$sesi[kode_sesi]</option>";
							}
							?>
						</select>
					</div>
					<div class='form-group'>
						<div class='row'>
							<div class='col-md-4'>
								<label>Lama ujian (menit)</label>
								<input type='number' name='lama_ujian' class='form-control' required='true' />
							</div>
							<div class='col-md-4'>
								<label>KKM</label>
								<input type='number' name='kkm' class='form-control' />
							</div>
							<div class='col-md-4'>
								<label>Jumlah Remidi</label>
								<input type='number' name='ulang' class='form-control' />
							</div>
						</div>
					</div>
					<div class='form-group'>
						<label></label><br>
						<label>
							<input type='checkbox' class='icheckbox_square-green' name='acak' value='1' $acak /> Acak Soal
						</label>

						<label>
							<input type='checkbox' class='icheckbox_square-green' name='token' value='1' $token /> Token Soal
						</label>

						<label>
							<input type='checkbox' class='icheckbox_square-green' name='hasil' value='1' $hasil /> Hasil Tampil
						</label>
					</div>
					<div class='modal-footer'>
						<button name='tambahjadwal' class='btn btn-sm btn-flat btn-success'><i class='fa fa-check'></i> Simpan Semua</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<div class='row'>
	<div class='col-md-12'>
		<div class='box box-solid'>
			<div class='box-header with-border '>
				<h3 class='box-title'><i class="fas fa-envelope-open-text    "></i> Aktifasi Ujian</h3>
				<div class='box-tools pull-right '>
					<?php if ($setting['server'] == 'pusat') : ?>

						<button class='btn btn-sm btn-flat btn-success' data-toggle='modal' data-backdrop='static' data-target='#tambahjadwal'><i class='glyphicon glyphicon-plus'></i> <span class='hidden-xs'>Tambah Jadwal</span></button>
					<?php endif ?>
				</div>
			</div><!-- /.box-header -->
			<div class='box-body'>
				<div class="col-md-1">
				</div>
				<div class="col-md-6">
					<form id='formaktivasi' action="">
						<div class="form-group">
							<label for="">Pilih Jadwal Ujian</label>
							<select class="form-control select2" name="ujian[]" style="width:100%" multiple='true' required>
								<?php $jadwal = mysqli_query($koneksi, "select * from ujian"); ?>
								<?php while ($ujian = mysqli_fetch_array($jadwal)) : ?>

									<option value="<?= $ujian['id_ujian'] ?>"><?= $ujian['nama'] . "-" . $ujian['id_pk'] ?></option>
								<?php endwhile; ?>
							</select>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-md-6">
									<label for="">Pilih Kelompok Test / Sesi</label>
									<select class="form-control select2" name="sesi" id="">
										<?php $sesi = mysqli_query($koneksi, "select * from siswa group by sesi"); ?>
										<?php while ($ses = mysqli_fetch_array($sesi)) : ?>
											<option value="<?= $ses['sesi'] ?>"><?= $ses['sesi'] ?></option>
										<?php endwhile; ?>
									</select>
								</div>
								<div class="col-md-6">
									<label for="">Pilih Aksi</label>
									<select class="form-control select2" name="aksi" required>

										<option value=""></option>
										<option value="1">aktif</option>
										<option value="0">non aktif</option>
										<option value="hapus">hapus</option>
									</select>
								</div>
							</div>
						</div>
						<button name="simpan" class="btn btn-success">Simpan Semua</button>
					</form>
				</div>
				<div class="col-md-4">
					<div class="box-body">
						<div class='small-box bg-aqua'>
							<div class='inner'>
								<?php $token = mysqli_fetch_array(mysqli_query($koneksi, "select token from token")) ?>
								<h3><span name='token' id='isi_token'><?= $token['token'] ?></span></h3>
								<p>Token Tes</p>
							</div>
							<div class='icon'>
								<i class='fa fa-barcode'></i>
							</div>
						</div>
						<a id="btntoken" href="#"><i class='fa fa-spin fa-refresh'></i> Refreh Sekarang</a>
						<p>Token akan refresh setiap 15 menit
					</div>
				</div>
			</div><!-- /.box -->
		</div>

	</div>
	<div id="bodyreset">
		<?php

		$mapelQ = mysqli_query($koneksi, "SELECT * FROM ujian ORDER BY status DESC, tgl_ujian ASC, waktu_ujian ASC");

		?>
		<?php while ($mapel = mysqli_fetch_array($mapelQ)) : ?>
			<?php
			if ($mapel['id_pk'] == '0') {
				$jur = 'Semua';
			} else {
				$jur = $mapel['id_pk'];
			}
			?>
			<div class="col-md-4">
				<!-- Widget: user widget style 1 -->
				<div class="box box-widget widget-user-2">
					<!-- Add the bg color to the header using any of the bg-* classes -->
					<div class="widget-user-header bg-blue" style="padding: 6px">
						<!-- <div class="widget-user-image">
							<i class="fas fa-envelope-open-text fa-5x    " alt="avatar"></i>
						</div> -->
						<!-- /.widget-user-image -->
						<h3 class="widget-user-username"><?= $mapel['nama'] ?></h3>
						<h5 class="widget-user-desc">
							<i class="fa fa-tag"></i> <?= $mapel['kode_ujian'] ?> &nbsp;
							<i class="fa fa-user"></i> <?= $mapel['level'] ?> &nbsp;
							<i class="fa fa-wrench"></i> <?= $jur ?>
						</h5>
					</div>
					<div class="box-footer no-padding">
						<ul class="nav nav-stacked">
							<li>
								<a href="#">
									<i class='fa fa-clock-o'></i> Ujian Dimulai
									<span class="pull-right badge bg-green"><?= $mapel['tgl_ujian'] ?></span>
								</a>
							</li>
							<li>
								<a href="#">
									<i class='fa fa-clock-o'></i> Ujian Ditutup
									<span class="pull-right badge bg-red"><?= $mapel['tgl_selesai'] ?></span>
								</a>
							</li>
							<li>
								<a href="#">
									<i class='fa  fa-hourglass-1'></i> Durasi Ujian
									<span class="pull-right badge bg-purple"><?= $mapel['tampil_pg'] ?> Soal / <?= $mapel['lama_ujian'] ?> menit</span>
								</a>
							</li>
							<li><a href="#"><i class='fa fa-feed'></i> Status Ujian <span class="pull-right">
										<?php
										if ($mapel['status'] == 1) {
											echo "<i class='fa fa-spinner fa-spin'></i> <label class='badge bg-green'>Sedang Aktif</label> <label class='badge bg-red'>Sesi $mapel[sesi]</label>";
										} elseif ($mapel['status'] == 0) {
											echo "<label class='badge bg-red'>Tidak Aktif</label>";
										}
										?>
									</span></a></li>
							<li>

							</li>

						</ul>
						<center style="padding: 8px">
							<a class="btn btn-primary " href="?pg=nilai&id=<?= $mapel['id_mapel'] ?>"><i class="fa fa-bar-chart-o "></i> Nilai</a>
							<a class="btn btn-primary " href="?pg=status&id=<?= $mapel['id_mapel'] ?>"><i class="fa fa-binoculars "></i> Status</a>
							<a class="btn btn-primary " href="?pg=banksoal&ac=lihat&id=<?= $mapel['id_mapel'] ?>"><i class="fa fa-search "></i> Soal</a>
							<!-- Button trigger modal -->
							<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modelId<?= $mapel['id_ujian'] ?>">
								<i class="fa fa-pencil-square-o"></i> edit
							</button>


						</center>
					</div>
				</div>
				<!-- /.widget-user -->
			</div>

			<div class='modal fade' id='modelId<?= $mapel['id_ujian'] ?>' style='display: none;'>
				<div class='modal-dialog'>
					<div class='modal-content'>
						<div class='modal-header bg-blue'>
							<h5 class='modal-title'>Edit Waktu Ujian</h5>

						</div>
						<form id="formedit<?= $mapel['id_ujian'] ?>">
							<div class='modal-body'>
								<input type='hidden' name='idm' value="<?= $mapel['id_ujian'] ?>" />
								<div class="form-group">
									<label for="mulaiujian">Waktu Mulai Ujian</label>
									<input type="text" class="tgl form-control" name="mulaiujian" value="<?= $mapel['tgl_ujian'] ?>" aria-describedby="helpId" placeholder="">
									<small id="helpId" class="form-text text-muted">Tanggal dan waktu ujian dibuka</small>
								</div>
								<div class="form-group">
									<label for="selesaiujian">Waktu Ujian Ditutup</label>
									<input type="text" class="tgl form-control" name="selesaiujian" value="<?= $mapel['tgl_selesai'] ?>" aria-describedby="helpId" placeholder="">
									<small id="helpId" class="form-text text-muted">Tanggal dan waktu ujian ditutup</small>
								</div>
							</div>
							<div class='modal-footer'>

								<center>
									<button type="submit" class='btn btn-primary' name='simpan'><i class='fa fa-save'></i> Ganti Waktu Ujian</button>
								</center>

							</div>
						</form>
					</div>
				</div>
			</div>

			<script>
				$("#formedit<?= $mapel['id_ujian'] ?>").submit(function(e) {
					e.preventDefault();
					$.ajax({
						type: 'POST',
						url: 'jadwal/edit_jadwal.php',
						data: $(this).serialize(),
						success: function(data) {
							location.reload();

						}
					});
					return false;
				});
			</script>
		<?php endwhile; ?>
	</div>

</div>

<script>
	$(document).ready(function() {
		$("#btntoken").click(function() {
			$.ajax({
				url: "_load.php?pg=token",
				type: "POST",
				success: function(respon) {
					$('#isi_token').html(respon);
					toastr.success('token berhasil diperbarui');
				}
			});
			return false;
		})
		$('#formaktivasi').submit(function(e) {
			e.preventDefault();
			$.ajax({
				type: 'POST',
				url: 'jadwalaktivasi.php?key=1616161616',
				data: $(this).serialize(),
				success: function(data) {
					if (data == 'hapus') {
						toastr.success('jadwal ujian berhasil di hapus');
					}
					if (data == 'update') {
						toastr.success('jadwal ujian berhasil diperbarui');
					}
					$('#bodyreset').load(location.href + ' #bodyreset');

				}
			});
			return false;
		});

	});
</script>
<script>
	$('#formtambahujian').submit(function(e) {
		e.preventDefault();
		$.ajax({
			type: 'POST',
			url: 'jadwal/tambah_jadwal.php',
			data: $(this).serialize(),
			success: function(data) {
				console.log(data);
				if (data == "OK") {
					toastr.success("jadwal berhasil dibuat");
				} else {
					toastr.error("jadwal gagal tersimpan");
				}
				$('#tambahjadwal').modal('hide');
				$('#bodyreset').load(location.href + ' #bodyreset');
			}
		});
		return false;
	});
</script>