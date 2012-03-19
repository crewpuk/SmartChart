	<form name="input_data" action="pages/proses.php" method="post" enctype="multipart/form-data" class="graph-input">
	<input type="hidden" name="sum_caption" id="sum_caption" value="2" />
	<input type="hidden" name="sum_x_axis" id="sum_x_axis" value="2" />
	<div class="wrapper">
	<div class="main-basket">
	<div class="basket top">
		<div class="left"></div>
		<div class="right">
			<div class="in"><input type="text" size="5" name="cap_1" id="cap_1" value="DKI Jakarta" /></div>
			<div class="in"><input type="text" size="5" name="cap_2" id="cap_2" value="Depok" /></div>
			<div class="remove" rel="cap_2" rev="2" title="Hapus kolom ini?">-</div>
		</div>
		<div class="clearer"></div>
		<div class="tambah top" title="Tambah kolom?">+</div>
	</div>
	<div class="basket bottom">
		<div class="left">
			<div class="in" align="center" rel="1"><input type="text" size="5" name="x_1" id="x_1" value="2011" /></div>
			<div class="in" align="center" rel="2"><input type="text" size="5" name="x_2" id="x_2" value="2012" /></div>
		</div>
		<div class="main">
		<div class="right" rel="1">
			<div class="in"><input type="text" size="5" name="v_1_1" id="v_1_1" value="2500000" /></div>
			<div class="in"><input type="text" size="5" name="v_1_2" id="v_1_2" value="1340000" /></div>
		</div>
		<div class="right" rel="2">
			<div class="in"><input type="text" size="5" name="v_2_1" id="v_2_1" value="2650000" /></div>
			<div class="in"><input type="text" size="5" name="v_2_2" id="v_2_2" value="1870000" /></div>
		</div>
		</div>
		<div class="clearer"></div>
		<div class="tambah bottom" title="Tambah baris?">+</div>
		<div class="remove_place_bottom"><div class="remove" rel="x_2" rev="2" title="Hapus baris ini?">-</div></div>
	</div>
	</div>
	<div class="info_input">
		<table cellpadding="5" cellspacing="0" border="0">
			<tr>
				<td width="150">Judul</td>
				<td><input type="text" name="title" value="Perbandingan Jumlah Penduduk" style="width: 300px;"></td>
			</tr>
			<tr>
				<td width="150">Sub-Judul</td>
				<td><input type="text" name="sub_title" value="2011-2012" style="width: 300px;"></td>
			</tr>
			<tr>
				<td width="150">Keterangan (x-axis)</td>
				<td><input type="text" name="x_info" value="Jiwa"></td>
			</tr>
		</table>
	</div>
	<div class="chart_option_play">
		<label><div class="chart line" align="center"><input type="radio" name="chart_option" id="chart_option" value="line" checked="checked" /></div></label>
		<label><div class="chart bar" align="center"><input type="radio" name="chart_option" id="chart_option" value="bar" /></div></label>
		<label><div class="chart pie" align="center"><input type="radio" name="chart_option" id="chart_option" value="pie" /></div></label>
	</div>
	<div class="clearer"></div>
		<div align="right"><input type="submit" name="btn_proses" value="Proses" style="margin-top: 20px;padding: 6px 30px;" /></div>
	</div>
	</form>