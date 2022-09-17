import TomSelect from "tom-select";

require('bootstrap');
require('@popperjs/core');
require('tom-select');

import './React/events/events';
import './React/tagscloud/tagscloud';
import './React/hebdoo/List';
import './React/blog/List';

(function () {

    const ratio = .1
    const options = {
        root: null,
        rootMargin: '0px',
        threshold: ratio
    }

    const handleIntersect = function (entries, observer) {
        entries.forEach(function (entry) {
            if(entry.intersectionRatio > ratio) {
                entry.target.classList.add('reveal-visible')
                observer.unobserve(entry.target)
            }
        })
    }

    const observer = new IntersectionObserver(handleIntersect, options);
    document.querySelectorAll('.reveal').forEach(function (r) {
        observer.observe(r)
    })

    let badges = document.querySelectorAll('.badgebox');
    if(badges) {
        for(let badge of badges){
            badge.addEventListener("click", function(){
                let $parent = this.parentElement;
                let $color = this.getAttribute('data-color');
                let $destIcon = this.getAttribute('data-icon');
                let $icon = document.getElementById($destIcon);

                if($parent.classList.contains('btn-outline-'+$color)) {
                    // uncheck -> check
                    $parent.classList.remove('btn-outline-'+$color);
                    $parent.classList.add('btn-'+$color);

                    if($icon){
                        $icon.classList.remove('bi-square');
                        $icon.classList.add('bi-check2-square');
                    }
                } else {
                    // check -> uncheck
                    $parent.classList.add('btn-outline-'+$color);
                    $parent.classList.remove('btn-'+$color);

                    if($icon){
                        $icon.classList.add('bi-square');
                        $icon.classList.remove('bi-check2-square');
                    }
                }
            });
        }
    }

    let newAuthor = document.getElementById('simple_links_author');
    if(newAuthor) {
        newAuthor.addEventListener('change', function () {
            let dest = document.getElementById('simple_links_author_new')
            if (this.value === '') {
                dest.classList.remove('d-none');
            } else {
                dest.classList.add('d-none');
            }
        })
    }

    let newEventAuthor = document.getElementById('events_author');
    if(newEventAuthor) {
        newEventAuthor.addEventListener('change', function () {
            let dest = document.getElementById('events_author_new')
            if (this.value === '') {
                dest.classList.remove('d-none');
            } else {
                dest.classList.add('d-none');
            }
        })
    }

    let toggle = document.querySelectorAll('.toggle');
    if(toggle) {
        for(let a of toggle){
            a.addEventListener('click', function(){
                let $dest = this.getAttribute('data-target');
                let $div = document.getElementById($dest);
                let $plus = this.getAttribute('data-plus');
                let $moins = this.getAttribute('data-moins');

                if($div.classList.contains('d-none')) {
                    $div.classList.remove('d-none');
                    this.innerHTML = $moins;
                } else {
                    $div.classList.add('d-none');
                    this.innerHTML = $plus;
                }
            })
        }
    }

    let settings = {
        create: true,
        createFilter: function(input) {
            input = input.toLowerCase();
            return !(input in this.options);
        }
    };
    let jstomselect = document.getElementById('jstomselect');
    if(jstomselect){
        new TomSelect('#jstomselect', settings);
    }
})();