<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <style>
    body {
      font-family: Arial, sans-serif;
      font-size: 12px;
    }
    .center {
      text-align: center;
    }
    .table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 1rem;
    }
    .table th, .table td {
      border: 1px solid black;
      padding: 4px;
    }
    .no-border td {
      border: none;
      padding: 2px;
    }
    .description td {
      vertical-align: top;
      padding: 6px;
    }
    .signature {
      width: 100%;
      margin-top: 2rem;
    }
    .signature td {
      text-align: center;
      vertical-align: top;
    }
    .header-logo {
      width: 60px;
    }
  </style>
</head>
<body>
<div style="display: flex; align-items: center; justify-content: center; border-bottom: 2px solid #000; padding-bottom: 8px; margin-bottom: 16px;">
  @if($logo_base64)
    <img src="data:image/png;base64,{{ $logo_base64 }}" alt="TPA Logo" style="height: 60px; margin-right: 16px;">
  @endif
  <div style="text-align: center;">
    <div style="font-size: 20px; font-weight: bold;">TAMAN PENDIDIKAN AL-QUR'AN ( TPA )</div>
    <div style="font-size: 22px; font-weight: bold; text-transform: uppercase;">{{ $institution_name }}</div>
    <div style="font-size: 14px;">{{ $institution_address }}</div>
  </div>
</div>

<table class="no-border">
  <tr><td>Nama Siswa</td><td>: {{ $student_name }}</td></tr>
  <tr><td>Semester</td><td>: {{ $semester }}</td></tr>
  <tr><td>Tahun Ajaran</td><td>: {{ $academic_year }}</td></tr>
</table>

<strong>A. ASPEK PENILAIAN JILID & AL-QUR'AN</strong>
<table class="table">
  <thead>
    <tr><th>Target</th><th>Prestasi</th><th colspan="4">Aspek Penilaian</th><th>Keterangan</th></tr>
    <tr><th colspan="2"></th><th>M</th><th>MAD</th><th>T</th><th>K</th><th></th></tr>
  </thead>
  <tbody>
    @forelse($quran_assessments as $assessment)
      <tr>
        <td>{{ $assessment['target'] }}</td>
        <td>{{ $assessment['achievement'] }}</td>
        <td>{{ $assessment['m'] }}</td>
        <td>{{ $assessment['mad'] }}</td>
        <td>{{ $assessment['t'] }}</td>
        <td>{{ $assessment['k'] }}</td>
        <td>{{ $assessment['description'] }}</td>
      </tr>
    @empty
      <tr><td colspan="7" style="text-align: center;">Tidak ada data</td></tr>
    @endforelse
  </tbody>
</table>

<strong>B. ASPEK PENILAIAN TAHFIDZ, DO'A DAN HADITS</strong>
<table class="table">
  <thead>
    <tr><th rowspan="2">Target Tahfidz</th><th colspan="4">Aspek Penilaian</th><th rowspan="2">Keterangan</th></tr>
    <tr><th>M</th><th>MAD</th><th>T</th><th>K</th></tr>
  </thead>
  <tbody>
    @forelse($tahfidz_assessments as $assessment)
      <tr>
        <td>{{ $assessment['target'] }}</td>
        <td>{{ $assessment['m'] }}</td>
        <td>{{ $assessment['mad'] }}</td>
        <td>{{ $assessment['t'] }}</td>
        <td>{{ $assessment['k'] }}</td>
        <td>{{ $assessment['description'] }}</td>
      </tr>
    @empty
      <tr><td colspan="6" style="text-align: center;">Tidak ada data</td></tr>
    @endforelse
  </tbody>
</table>

<table class="table">
  <thead>
    <tr><th rowspan="2">Target Do'a dan Hadits</th><th colspan="2">Aspek Penilaian</th><th rowspan="2">Keterangan</th></tr>
    <tr><th>Fashohah</th><th>Kelancaran</th></tr>
  </thead>
  <tbody>
    @forelse($doa_hadits_assessments as $assessment)
      <tr>
        <td>{{ $assessment['target'] }}</td>
        <td>{{ $assessment['fashohah'] }}</td>
        <td>{{ $assessment['kelancaran'] }}</td>
        <td>{{ $assessment['description'] }}</td>
      </tr>
    @empty
      <tr><td colspan="4" style="text-align: center;">Tidak ada data</td></tr>
    @endforelse
  </tbody>
</table>

<strong>C. DESKRIPSI</strong>
<table class="table description">
  <thead>
    <tr><th>Aspek</th><th>Catatan</th></tr>
  </thead>
  <tbody>
    @forelse($grade_aspects as $aspect)
      <tr>
        <td>{{ $aspect['aspect'] }}</td>
        <td><em>{{ $aspect['note'] }}</em></td>
      </tr>
    @empty
      <tr><td colspan="2" style="text-align: center;">Tidak ada catatan</td></tr>
    @endforelse
  </tbody>
</table>

<table class="signature">
  <tr>
    <td>Orangtua/wali santri</td>
    <td>Ustadz/ah</td>
    <td>{{ $location }}, {{ $report_date }}<br>Kepala TPA {{ $institution_name }}</td>
  </tr>
  <tr>
    <td style="padding-top: 60px;">&nbsp;</td>
    <td style="padding-top: 60px;">{{ $teacher_name }}</td>
    <td style="padding-top: 60px;">{{ $principal_name }}</td>
  </tr>
</table>
</body>
</html>
