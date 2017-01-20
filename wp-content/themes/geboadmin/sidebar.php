<?php

if (!is_user_logged_in()) {
    auth_redirect();
}

global $current_user, $post;

$post_slug = trim($post->post_name);
?>
<a href="javascript:void(0)" class="sidebar_switch on_switch ttip_r" title="Hide Sidebar">Sidebar switch</a>
<div class="sidebar">
    <div class="antiScroll">
        <div class="antiscroll-inner">
            <div class="antiscroll-content">

                <div class="sidebar_inner">
                    <!--                    <form action="index.php?uid=1&amp;page=search_page" class="input-append" method="post" >-->
                    <!--                        <input autocomplete="off" name="query" class="search_query input-medium" size="16" type="text" placeholder="Search..." /><button type="submit" class="btn"><i class="icon-search"></i></button>-->
                    <!--                    </form>-->
                    <div id="side_accordion" class="accordion">
                        <?php if (in_array('administrator', $current_user->roles) OR in_array('client_admin', $current_user->roles)) { ?>
                            <div class="accordion-group">
                                <div class="accordion-heading">
                                    <a href="#collapseOne" data-parent="#side_accordion" data-toggle="collapse"
                                       class="accordion-toggle">
                                        <i class="icon-user"></i> Użytkownicy
                                    </a>
                                </div>
                                <div class="accordion-body collapse<?php echo(in_array($post_slug, array(
                                    'uzytkownicy',
                                    'profil'
                                )) ? ' in' : '') ?>" id="collapseOne">
                                    <div class="accordion-inner">
                                        <ul class="nav nav-list">
                                            <li<?php echo($post_slug == 'uzytkownicy' && empty($_REQUEST['action']) ? ' class="active"' : '') ?>>
                                                <a href="<?php echo get_bloginfo('url').'/uzytkownicy'; ?>">Lista
                                                    użytkowników</a></li>
                                            <li<?php echo($post_slug == 'profil' ? ' class="active"' : '') ?>><a
                                                    href="<?php echo get_bloginfo('url').'/profil'; ?>">Edytuj
                                                    prolil</a>
                                            </li>
                                            <li<?php echo($post_slug == 'uzytkownicy' && $_REQUEST['action'] == 'add' ? ' class="active"' : '') ?>>
                                                <a href="<?php echo get_bloginfo('url').'/uzytkownicy?action=add'; ?>">Dodaj
                                                    użytkownika</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="accordion-group">
                            <div class="accordion-heading">
                                <a href="#collapseTwo" data-parent="#side_accordion" data-toggle="collapse"
                                   class="accordion-toggle">
                                    <i class="icon-th"></i> Klienci
                                </a>
                            </div>
                            <div
                                class="accordion-body collapse<?php echo(in_array($post_slug, array('klienci')) ? ' in' : '') ?>"
                                id="collapseTwo">
                                <div class="accordion-inner">
                                    <ul class="nav nav-list">
                                        <li<?php echo($post_slug == 'klienci' && empty($_REQUEST['action']) ? ' class="active"' : '') ?>>
                                            <a href="<?php echo get_bloginfo('url').'/klienci'; ?>">Lista klientów</a>
                                        </li>
                                        <li<?php echo($post_slug == 'klienci' && $_REQUEST['action'] == 'add' ? ' class="active"' : '') ?>>
                                            <a href="<?php echo get_bloginfo('url').'/klienci?action=add'; ?>">Dodaj
                                                klienta</a></li>
                                        <?php
                                        if (is_app_admin($current_user)) {
                                            ?>
                                            <li<?php echo($post_slug == 'klienci' && $_REQUEST['action'] == 'limit' ? ' class="active"' : '') ?>>
                                                <a href="<?php echo get_bloginfo('url').'/klienci?action=limit'; ?>">Limity
                                                    nowych klientów</a></li>
                                            <?php
                                        }
                                        ?>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-group">
                            <div class="accordion-heading">
                                <a href="#collapseFour" data-parent="#side_accordion" data-toggle="collapse"
                                   class="accordion-toggle">
                                    <i class="icon-folder-close"></i> Zlecenia
                                </a>
                            </div>
                            <div
                                class="accordion-body collapse<?php echo(in_array($post_slug, array('zlecenia')) ? ' in' : '') ?>"
                                id="collapseFour">
                                <div class="accordion-inner">
                                    <ul class="nav nav-list">

                                        <li<?php echo($post_slug == 'zlecenia' && empty($_REQUEST['action']) ? ' class="active"' : '') ?>>
                                            <a href="<?php echo get_bloginfo('url').'/zlecenia'; ?>">Lista zleceń</a>
                                        </li>
                                        <li<?php echo($post_slug == 'zlecenia' && $_REQUEST['action'] == 'add' ? ' class="active"' : '') ?>>
                                            <a href="<?php echo get_bloginfo('url').'/zlecenia?action=add'; ?>">Dodaj
                                                zlecenie</a></li>
                                        <!--                                        <li class="nav-header">System</li>-->
                                        <!--                                        <li><a href="javascript:void(0)">Site information</a></li>-->
                                        <!--                                        <li><a href="javascript:void(0)">Actions</a></li>-->
                                        <!--                                        <li><a href="javascript:void(0)">Cron</a></li>-->
                                        <!--                                        <li class="divider"></li>-->
                                        <!--                                        <li><a href="javascript:void(0)">Help</a></li>-->
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-group">
                            <div class="accordion-heading">
                                <a href="#collapseNota" data-parent="#side_accordion" data-toggle="collapse"
                                   class="accordion-toggle">
                                    <i class="icon-folder-close"></i> Noty
                                </a>
                            </div>
                            <div
                                class="accordion-body collapse<?php echo(in_array($post_slug, array('noty')) ? ' in' : '') ?>"
                                id="collapseNota">
                                <div class="accordion-inner">
                                    <ul class="nav nav-list">
                                        <li<?php echo($post_slug == 'noty' && empty($_REQUEST['action']) ? ' class="active"' : '') ?>>
                                            <a href="<?php echo get_bloginfo('url').'/noty'; ?>">Lista not </a></li>
                                        <li<?php echo($post_slug == 'noty' && $_REQUEST['action'] == 'add' ? ' class="active"' : '') ?>>
                                            <a href="<?php echo get_bloginfo('url').'/noty?action=add'; ?>">Dodaj
                                                notę</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-group">
                            <div class="accordion-heading">
                                <a href="#collapsePodw" data-parent="#side_accordion" data-toggle="collapse"
                                   class="accordion-toggle">
                                    <i class="icon-cog"></i> Podwykonawcy
                                </a>
                            </div>
                            <div
                                class="accordion-body collapse<?php echo(in_array($post_slug, array('podwykonawcy')) ? ' in' : '') ?>"
                                id="collapsePodw">
                                <div class="accordion-inner">
                                    <ul class="nav nav-list">
                                        <li<?php echo($post_slug == 'podwykonawcy' && empty($_REQUEST['action']) ? ' class="active"' : '') ?>>
                                            <a href="<?php echo get_bloginfo('url').'/podwykonawcy'; ?>">Lista
                                                podwykonawców</a></li>
                                        <li<?php echo($post_slug == 'podwykonawcy' && $_REQUEST['action'] == 'add' ? ' class="active"' : '') ?>>
                                            <a href="<?php echo get_bloginfo('url').'/podwykonawcy?action=add'; ?>">Dodaj
                                                podwykonawcę</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-group">
                            <div class="accordion-heading">
                                <a href="#collapseLong" data-parent="#side_accordion" data-toggle="collapse"
                                   class="accordion-toggle">
                                    <i class="icon-leaf"></i> Faktury
                                </a>
                            </div>
                            <div class="accordion-body collapse<?php echo(in_array($post_slug, array(
                                'faktury',
                                'pozycje',
                                'korekty',
                                'grupowe'
                            )) ? ' in' : '') ?>" id="collapseLong">
                                <div class="accordion-inner">
                                    <ul class="nav nav-list">
                                        <li<?php echo($post_slug == 'pozycje' ? ' class="active"' : '') ?>><a
                                                href="<?php echo get_bloginfo('url').'/pozycje'; ?>">Pozycje na
                                                fakturze</a></li>
                                        <li<?php echo($post_slug == 'faktury' && empty($_REQUEST['action']) ? ' class="active"' : '') ?>>
                                            <a href="<?php echo get_bloginfo('url').'/faktury'; ?>">Sprzedaż</a></li>
                                        <li<?php echo($post_slug == 'faktury' && $_REQUEST['action'] == 'zakup' ? ' class="active"' : '') ?>>
                                            <a href="<?php echo get_bloginfo('url').'/faktury?action=zakup'; ?>">Zakup</a>
                                        </li>
                                        <li<?php echo($post_slug == 'faktury' && $_REQUEST['action'] == 'addsp' ? ' class="active"' : '') ?>>
                                            <a href="<?php echo get_bloginfo('url').'/faktury?action=addsp'; ?>">Dodaj
                                                fakturę sprzedaży</a></li>
                                        <li<?php echo($post_slug == 'korekty' ? ' class="active"' : '') ?>><a
                                                href="<?php echo get_bloginfo('url').'/korekty'; ?>">Faktury
                                                korygujące</a></li>
                                        <li<?php echo($post_slug == 'grupowe' && empty($_REQUEST['action']) ? ' class="active"' : '') ?>>
                                            <a href="<?php echo get_bloginfo('url').'/grupowe'; ?>">Faktury grupowe</a>
                                        </li>
                                        <li<?php echo($post_slug == 'grupowe' && $_REQUEST['action'] == 'add' ? ' class="active"' : '') ?>>
                                            <a href="<?php echo get_bloginfo('url').'/grupowe?action=add'; ?>">Dodaj
                                                fakturę grupową</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <?php if (is_app_admin($current_user)) { ?>
                            <div class="accordion-group">
                                <div class="accordion-heading">
                                    <a href="#collapseKoszt" data-parent="#side_accordion" data-toggle="collapse"
                                       class="accordion-toggle">
                                        <i class="icon-folder-close"></i> Koszty stałe
                                    </a>
                                </div>
                                <div
                                    class="accordion-body collapse<?php echo(in_array($post_slug, array('koszty')) ? ' in' : '') ?>"
                                    id="collapseKoszt">
                                    <div class="accordion-inner">
                                        <ul class="nav nav-list">
                                            <li<?php echo($post_slug == 'koszty' && empty($_REQUEST['action']) ? ' class="active"' : '') ?>>
                                                <a href="<?php echo get_bloginfo('url').'/koszty'; ?>">Lista
                                                    kosztów </a>
                                            </li>
                                            <li<?php echo($post_slug == 'koszty' && $_REQUEST['action'] == 'add' ? ' class="active"' : '') ?>>
                                                <a href="<?php echo get_bloginfo('url').'/koszty?action=add'; ?>">Dodaj
                                                    koszt</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                        <?php } ?>


                        <div class="accordion-group">
                            <div class="accordion-heading">
                                <a href="#collapseRap" data-parent="#side_accordion" data-toggle="collapse"
                                   class="accordion-toggle">
                                    <i class="icon-leaf"></i> Raporty
                                </a>
                            </div>
                            <div
                                class="accordion-body collapse<?php echo(in_array($post_slug, array('raporty')) ? ' in' : '') ?>"
                                id="collapseRap">
                                <div class="accordion-inner">
                                    <ul class="nav nav-list">
                                        <li<?php echo($post_slug == 'raporty' && $_REQUEST['action'] == 'klienci' ? ' class="active"' : '') ?>>
                                            <a href="<?php echo get_bloginfo('url').'/raporty?action=klienci'; ?>">Klienci</a>
                                        </li>
                                        <li<?php echo($post_slug == 'raporty' && $_REQUEST['action'] == 'zlecenia' ? ' class="active"' : '') ?>>
                                            <a href="<?php echo get_bloginfo('url').'/raporty?action=zlecenia'; ?>">Zlecenia</a>
                                        </li>
                                        <?php if (is_app_admin($current_user)) { ?>
                                            <li<?php echo($post_slug == 'raporty' && $_REQUEST['action'] == 'koszty' ? ' class="active"' : '') ?>>
                                                <a href="<?php echo get_bloginfo('url').'/raporty?action=koszty'; ?>">Koszty</a>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-group">
                            <div class="accordion-heading">
                                <a href="#collapse7" data-parent="#side_accordion" data-toggle="collapse"
                                   class="accordion-toggle">
                                    <i class="icon-th"></i> Kalkulator
                                </a>
                            </div>
                            <div class="accordion-body collapse" id="collapse7">
                                <div class="accordion-inner">
                                    <form name="Calc" id="calc">
                                        <div class="formSep control-group input-append">
                                            <input type="text" style="width:142px" name="Input"/>
                                            <button type="button" class="btn" name="clear" value="c"
                                                    onclick="Calc.Input.value = ''"><i class="icon-remove"></i></button>
                                        </div>
                                        <div class="control-group">
                                            <input type="button" class="btn btn-large" name="seven" value="7"
                                                   onclick="Calc.Input.value += '7'"/>
                                            <input type="button" class="btn btn-large" name="eight" value="8"
                                                   onclick="Calc.Input.value += '8'"/>
                                            <input type="button" class="btn btn-large" name="nine" value="9"
                                                   onclick="Calc.Input.value += '9'"/>
                                            <input type="button" class="btn btn-large" name="div" value="/"
                                                   onclick="Calc.Input.value += ' / '">
                                        </div>
                                        <div class="control-group">
                                            <input type="button" class="btn btn-large" name="four" value="4"
                                                   onclick="Calc.Input.value += '4'"/>
                                            <input type="button" class="btn btn-large" name="five" value="5"
                                                   onclick="Calc.Input.value += '5'"/>
                                            <input type="button" class="btn btn-large" name="six" value="6"
                                                   onclick="Calc.Input.value += '6'"/>
                                            <input type="button" class="btn btn-large" name="times" value="x"
                                                   onclick="Calc.Input.value += ' * '"/>
                                        </div>
                                        <div class="control-group">
                                            <input type="button" class="btn btn-large" name="one" value="1"
                                                   onclick="Calc.Input.value += '1'"/>
                                            <input type="button" class="btn btn-large" name="two" value="2"
                                                   onclick="Calc.Input.value += '2'"/>
                                            <input type="button" class="btn btn-large" name="three" value="3"
                                                   onclick="Calc.Input.value += '3'"/>
                                            <input type="button" class="btn btn-large" name="minus" value="-"
                                                   onclick="Calc.Input.value += ' - '"/>
                                        </div>
                                        <div class="formSep control-group">
                                            <input type="button" class="btn btn-large" name="dot" value="."
                                                   onclick="Calc.Input.value += '.'"/>
                                            <input type="button" class="btn btn-large" name="zero" value="0"
                                                   onclick="Calc.Input.value += '0'"/>
                                            <input type="button" class="btn btn-large" name="DoIt" value="="
                                                   onclick="Calc.Input.value = Math.round( eval(Calc.Input.value) * 1000)/1000"/>
                                            <input type="button" class="btn btn-large" name="plus" value="+"
                                                   onclick="Calc.Input.value += ' + '"/>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="push"></div>
                </div>

                <!--                <div class="sidebar_info">-->
                <!--                    <ul class="unstyled">-->
                <!--                        <li>-->
                <!--                            <span class="act act-warning">65</span>-->
                <!--                            <strong>New comments</strong>-->
                <!--                        </li>-->
                <!--                        <li>-->
                <!--                            <span class="act act-success">10</span>-->
                <!--                            <strong>New articles</strong>-->
                <!--                        </li>-->
                <!--                        <li>-->
                <!--                            <span class="act act-danger">85</span>-->
                <!--                            <strong>New registrations</strong>-->
                <!--                        </li>-->
                <!--                    </ul>-->
                <!--                </div>-->

            </div>
        </div>
    </div>

</div>