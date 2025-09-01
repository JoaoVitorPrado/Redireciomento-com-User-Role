<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// ==============================
// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:
// ==============================

if ( !function_exists( 'chld_thm_cfg_locale_css' ) ):
    function chld_thm_cfg_locale_css( $uri ) {
        if ( empty( $uri ) && is_rtl() && file_exists( get_template_directory() . '/rtl.css' ) ) {
            $uri = get_template_directory_uri() . '/rtl.css';
        }
        return $uri;
    }
endif;

add_filter( 'locale_stylesheet_uri', 'chld_thm_cfg_locale_css' );

if ( !function_exists( 'child_theme_configurator_css' ) ):
    function child_theme_configurator_css() {
        wp_enqueue_style(
            'chld_thm_cfg_child',
            trailingslashit( get_stylesheet_directory_uri() ) . 'style.css',
            array(
                'hello-elementor',
                'hello-elementor',
                'hello-elementor-theme-style',
                'hello-elementor-header-footer'
            )
        );
    }
endif;

add_action( 'wp_enqueue_scripts', 'child_theme_configurator_css', 10 );

// ==============================
// END ENQUEUE PARENT ACTION
// ==============================


// ==============================
// Remove a barra de admin
// ==============================
add_filter('show_admin_bar', '__return_false');


// ==============================
// Diferenciação de cargo ao clicar em desempenho
// ==============================

// Adiciona o shortcode [user_role] para exibir o cargo do usuário logado
function user_role_shortcode() {
    // Obtém o objeto do usuário atual
    $current_user = wp_get_current_user();

    // Verifica se o usuário está logado
    if ( $current_user->exists() ) {
        $roles = $current_user->roles;

        // Se houver cargos
        if ( ! empty( $roles ) ) {
            // Se for subscriber ou administrador
            if ( $roles[0] == "subscriber" || $roles[0] == "administrator" ) {
                return '
                </a>
                <a href="https://interno.maxigrafica.com.br/ac-v2-interno/" id="btnac">
                    <img decoding="async" width="80" height="80"
                         src="https://interno.maxigrafica.com.br/wp-content/uploads/2024/09/analise_critica-150x150.png"
                         class="attachment-thumbnail size-thumbnail wp-image-452" alt=""
                         srcset="https://interno.maxigrafica.com.br/wp-content/uploads/2024/09/analise_critica-150x150.png 150w,
                                 https://interno.maxigrafica.com.br/wp-content/uploads/2024/09/analise_critica-300x300.png 300w,
                                 https://interno.maxigrafica.com.br/wp-content/uploads/2024/09/analise_critica.png 512w"
                         sizes="(max-width: 150px) 100vw, 150px">
                </a>';
            }
            // Se for contributor
            else if ( $roles[0] == "contributor" ) {
                return '
                </a>
                <a href="https://interno.maxigrafica.com.br/ac-v2-externo/" id="btnac">
                    <img decoding="async" width="80" height="80"
                         src="https://interno.maxigrafica.com.br/wp-content/uploads/2024/09/analise_critica-150x150.png"
                         class="attachment-thumbnail size-thumbnail wp-image-452" alt=""
                         srcset="https://interno.maxigrafica.com.br/wp-content/uploads/2024/09/analise_critica-150x150.png 150w,
                                 https://interno.maxigrafica.com.br/wp-content/uploads/2024/09/analise_critica-300x300.png 300w,
                                 https://interno.maxigrafica.com.br/wp-content/uploads/2024/09/analise_critica.png 512w"
                         sizes="(max-width: 150px) 100vw, 150px">
                </a>';
            }
        } else {
            return 'O usuário não tem cargo atribuído.';
        }
    } else {
        return 'Nenhum usuário está logado.';
    }
}
add_shortcode( 'user_role', 'user_role_shortcode' );


// ==============================
// Códigos feitos para o JetFormBuilder
// ==============================

// Shortcode para campos de datas personalizados
function custom_date_field_shortcode($atts) {
    $fieldNames = array("data_recebimento", "data_aprovacao", "data_entrega");
    $html = '';

    for ($i = 0; $i < count($fieldNames); $i++) {
        $field_atts = shortcode_atts(array(
            'name' => $fieldNames[$i],
            'id' => ($fieldNames[$i] . '_id'),
            'class' => 'custom-date-class',
            'value' => '',
            'required'=> 'required',
            'data-jfb-sync'=> 'null',
            'pattern' => '[0-9]{2}-[0-9]{2}-[0-9]{4}',
            'label' => substr($fieldNames[$i], 5),
        ), $atts, 'date_field');

        $html .= '
        <div class="wp-block-column is-layout-flow wp-block-column-is-layout-flow">
            <div class="jet-form-builder-row field-type-date-field">
                <div class="jet-form-builder__label">
                    <div class="jet-form-builder__label-text">
                        Data de ' . esc_attr($field_atts['label']) . '
                        <span class="jet-form-builder__required">*</span>
                    </div>
                </div>
                <input type="date" 
                       name="' . esc_attr($field_atts['name']) . '" 
                       id="' . esc_attr($field_atts['id']) . '" 
                       class="' . esc_attr($field_atts['class']) . '" 
                       data-jfb-sync="'. esc_attr($field_atts['data-jfb-sync']) .'" 
                       required="' . esc_attr($field_atts['required']) . '" 
                       pattern="' . esc_attr($field_atts['pattern']) . '">
            </div>
        </div>';
    }

    return '
    <div class="wp-block-columns is-layout-flex wp-container-core-columns-is-layout-3 wp-block-columns-is-layout-flex">' . $html . '</div>';
}
add_shortcode('date_field', 'custom_date_field_shortcode');


// Script de sincronização das datas
function enqueue_date_sync_script() {
    ob_start(); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var fieldID = ['data_recebimento', 'data_aprovacao', 'data_entrega'];

            // Função para obter a data atual no formato YYYY-MM-DD
            function getTodayDate() {
                const today = new Date();
                const year = today.getFullYear();
                const month = String(today.getMonth() + 1).padStart(2, '0');
                const day = String(today.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            }

            // Função para sincronizar e definir valores mínimos
            function sync(fieldName) {
                var dateField = document.getElementById(fieldName + '_id');
                var hiddenField = document.getElementById('hidden_' + fieldName);

                if (fieldName === 'data_recebimento') {
                    dateField.min = getTodayDate();
                }

                dateField.addEventListener('change', function() {
                    var dateValue = dateField.value;
                    if (dateValue) {
                        const data = new Date(dateValue + 'T00:00:00');
                        data.setHours(data.getHours() + 3);
                        const dia = String(data.getDate()).padStart(2, '0');
                        const mes = String(data.getMonth() + 1).padStart(2, '0');
                        const ano = data.getFullYear();
                        var formattedDate = `${dia}-${mes}-${ano}`;

                        hiddenField.value = formattedDate;

                        if (fieldName === 'data_recebimento') {
                            document.getElementById('data_aprovacao_id').min = dateValue;
                        } else if (fieldName === 'data_aprovacao') {
                            document.getElementById('data_entrega_id').min = dateValue;
                        }
                    }
                });
            }

            for (var i = 0; i < fieldID.length; i++) {
                sync(fieldID[i]);
            }
        });
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode('date_sync_script', 'enqueue_date_sync_script');


// ==============================
// Função para adicionar cargo temp_user
// ==============================
add_role('temp_user', 'Temp User', array(
    'read' => true,
    'edit_posts' => false,
    'delete_posts' => false,
));
