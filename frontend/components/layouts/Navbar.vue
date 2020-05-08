<template>
    <nav class="navbar sticky-top navbar-expand-lg navbar-light bg-light mb-2 py-0 lsp-nav--1">
        <div class="container">
            <nuxt-link to="/" class="navbar-brand">
                Logo
            </nuxt-link>
            <div class="lsp-logo__title">
                <span class="d-block">Title</span>
            </div>
            <button data-widget="pushmenu" class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-two1" aria-controls="navbar-two1" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbar-two">
                <div class="navbar-nav ml-auto text-uppercase">
                    <nuxt-link to="/" class="nav-item nav-link text-dark">Home</nuxt-link>
                    <nuxt-link to="/login" class="nav-item nav-link text-dark">Login</nuxt-link>
                </div>
            </div>
        </div>    
    </nav>
</template>


<script>
    import $ from 'jquery'
    export default {
        props: {
            lsp: {
                type: Object,
                default: () => ({}),
            },
            menus: {
                type: Object,
                default: () => ({}),
            }
        },
        mounted() {
            const $dropdown = $('.dropdown')
            const $dropdownToggle = $('.dropdown-toggle')
            const $dropdownMenu = $('.dropdown-menu')
            const showClass = 'show'
                
            $(window).on('load resize', function() {
                if (this.matchMedia('(min-width: 768px)').matches) {
                    $dropdown.hover(
                        function() {
                            const $this = $(this)
                            $this.addClass(showClass)
                            $this.find($dropdownToggle).attr('aria-expanded', 'true')
                            $this.find($dropdownMenu).addClass(showClass)
                        },
                        function() {
                            const $this = $(this)
                            $this.removeClass(showClass)
                            $this.find($dropdownToggle).attr('aria-expanded', 'false')
                            $this.find($dropdownMenu).removeClass(showClass)
                        }
                    )
                } else {
                    $dropdown.off('mouseenter mouseleave')
                }
            })

            setTimeout(() => {
                $('#navbar-two .dropdown-item').on('click', () => {
                    $('#navbar-two .dropdown').removeClass('show')
                    $('#navbar-two .dropdown-toggle').attr('aria-expanded', 'false')
                    $('#navbar-two .dropdown-menu').removeClass('show')
                })
            }, 1000)
        }
    }
</script>


<style lang="scss" scoped>
    .lsp-logo {
        width: 60px;

        @media screen and (min-width: 991px) {
            width: 80px;
        }
    }

    .lsp-logo__title {
        display: none;

        @media screen and (min-width: 991px) {
            display: block;
        }
    }
</style>