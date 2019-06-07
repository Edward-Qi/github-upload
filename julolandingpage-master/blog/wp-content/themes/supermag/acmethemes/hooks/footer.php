<?php
/**
 * content and content wrapper end
 *
 * @since SuperMag 1.0.0
 *
 * @param null
 * @return null
 *
 */
if ( ! function_exists( 'supermag_after_content' ) ) :

    function supermag_after_content() {
      ?>
        </div><!-- #content -->
        </div><!-- content-wrapper-->
    <?php
    }
endif;
add_action( 'supermag_action_after_content', 'supermag_after_content', 10 );

/**
 * Footer content
 *
 * @since SuperMag 1.0.0
 *
 * @param null
 * @return null
 *
 */
if ( ! function_exists( 'supermag_footer' ) ) :

    function supermag_footer() {

	    $supermag_customizer_all_values = supermag_get_theme_options();
	    if( is_active_sidebar( 'full-width-footer' ) ) :
		    dynamic_sidebar( 'full-width-footer' );
	    endif;
        ?>
        <div class="clearfix"></div>
        <footer id="colophon" class="site-footer" role="contentinfo">
            <div class="footer-wrapper">
            <section class="mbr-section cid-qM3NeG6ij9 mbr-parallax-background" id="footercontent">

<div class="container">
    <div class="mdia-container-row row">
        <div class="mbr-text col-sm-8 display-7" style="font-size:0.7rem;">
            <p>PERHATIAN :</p>
            <ol type="i">
                <li>Layanan Pinjam Meminjam Berbasis Teknologi Informasi merupakan kesepakatan perdata antara
                    Pemberi Pinjaman dengan Penerima Pinjaman, sehingga segala risiko yang timbul dari kesepakatan
                    tersebut ditanggung sepenuhnya oleh masing-masing pihak.</li>
                <br>

                <li>Risiko kredit atau gagal bayar ditanggung sepenuhnya oleh Pemberi Pinjaman. Tidak ada lembaga
                    atau otoritas negara yang bertanggung jawab atas risiko gagal bayar ini.</li>
                <br>

                <li>Penyelenggara dengan persetujuan dari masing-masing Pengguna (Pemberi Pinjaman dan/atau Penerima
                    Pinjaman) mengakses, memperoleh, menyimpan, mengelola dan/atau menggunakan data pribadi Pengguna
                    ("Pemanfaatan Data") pada atau di dalam benda, perangkat elektronik (termasuk smartphone
                    atau telepon seluler), perangkat keras (hardware) maupun lunak (software), dokumen elektronik,
                    aplikasi atau sistem elektronik milik Pengguna atau yang dikuasai Pengguna, dengan memberitahukan
                    tujuan, batasan dan mekanisme Pemanfaatan Data tersebut kepada Pengguna yang bersangkutan
                    sebelum memperoleh persetujuan yang dimaksud.</li>
                <br>

                <li>Pemberi Pinjaman yang belum memiliki pengetahuan dan pengalaman pinjam meminjam, disarankan
                    untuk tidak menggunakan layanan ini.</li>
                <br>

                <li>Penerima Pinjaman harus mempertimbangkan tingkat bunga pinjaman dan biaya lainnya sesuai
                    dengan kemampuan dalam melunasi pinjaman.</li>
                <br>

                <li>Setiap kecurangan tercatat secara digital di dunia maya dan dapat diketahui masyarakat luas
                    di media sosial.</li>
                <br>

                <li>Pengguna harus membaca dan memahami informasi ini sebelum membuat keputusan menjadi Pemberi
                    Pinjaman atau Penerima Pinjaman.</li>
                <br>

                <li>Pemerintah yaitu dalam hal ini Otoritas Jasa Keuangan, tidak bertanggung jawab atas setiap
                    pelanggaran atau ketidakpatuhan oleh Pengguna, baik Pemberi Pinjaman maupun Penerima Pinjaman
                    (baik karena kesengajaan atau kelalaian Pengguna) terhadap ketentuan peraturan perundang-undangan
                    maupun kesepakatan atau perikatan antara Penyelenggara dengan Pemberi Pinjaman dan/atau Penerima
                    Pinjaman.</li>
                <br>

                <li>Setiap transaksi dan kegiatan pinjam meminjam atau pelaksanaan kesepakatan mengenai pinjam
                    meminjam antara atau yang melibatkan Penyelenggara, Pemberi Pinjaman dan/atau Penerima Pinjaman
                    wajib dilakukan melalui escrow account dan virtual account sebagaimana yang diwajibkan berdasarkan
                    Peraturan Otoritas Jasa Keuangan Nomor 77/POJK.01/2016 tentang Layanan Pinjam Meminjam Uang
                    Berbasis Teknologi Informasi dan pelanggaran atau ketidakpatuhan terhadap ketentuan tersebut
                    merupakan bukti telah terjadinya pelanggaran hukum oleh Penyelenggara sehingga Penyelenggara
                    wajib menanggung ganti rugi yang diderita oleh masingmasing Pengguna sebagai akibat langsung
                    dari pelanggaran hukum tersebut di atas tanpa mengurangi hak Pengguna yang menderita kerugian
                    menurut Kitab Undang-Undang Hukum Perdata.</li>
                <br>
            </ol>

        </div>
        <div class="mbr-text col-md-4 display-7">
            <p style="text-align:center;margin-bottom:0;">Terdaftar dan diawasi oleh</p>
            <div class="logo-ojk">
                <img src="<?php echo get_template_directory_uri();?>/assets/img/pinjaman_online_JULO_terdaftar_OJK.svg" alt="Pinjaman Online JULO Terdaftar OJK" >
            </div>
            <p>PT. JULO TEKNOLOGI FINANSIAL merupakan perusahaan penyedia layanan pinjaman online yang terdaftar
                dan berada dalam pengawasan oleh Otoritas Jasa Keuangan (OJK) dengan nomor registrasi S-589/NB.213/2018
                sesuai dengan Hukum Republik Indonesia dan Peraturan Otoritas Jasa Keuangan nomor 77/POJK.01/2016.
                Oleh karena itu, PT JULO TEKNOLOGI FINANSIAL berkomitmen untuk menjunjung tinggi integritas dan
                melaksanakan perlindungan konsumen sesuai dengan hukum yang berlaku.</p>
        </div>
    </div>
</div>

</section>
                <div class="wrapper footer-copyright border text-center">
                    <p>
                        <?php if( isset( $supermag_customizer_all_values['supermag-footer-copyright'] ) ): ?>
                            <?php echo wp_kses_post( $supermag_customizer_all_values['supermag-footer-copyright'] ); ?>
                        <?php endif; ?>
                    </p>
                    <div class="site-info">
                   
                    </div><!-- .site-info -->
                </div>
            </div><!-- footer-wrapper-->
        </footer><!-- #colophon -->
    <?php
    }
endif;
add_action( 'supermag_action_footer', 'supermag_footer', 10 );

/**
 * Page end
 *
 * @since SuperMag 1.1.0
 *
 * @param null
 * @return null
 *
 */
if ( ! function_exists( 'supermag_page_end' ) ) :

    function supermag_page_end() {
        ?>
        </div><!-- #page -->
    <?php
    }
endif;
add_action( 'supermag_action_after', 'supermag_page_end', 10 );