import React, {useEffect} from "react";
import {render, unmountComponentAtNode} from "react-dom";
import {usePaginatedFetch} from "../hooks";

function Tagscloud (props) {
    const {items: tags, load} = usePaginatedFetch('/api/tags');
    const {baseUrl} = props

    useEffect(() => {
        load()
    }, [])

    return <div>
        {tags.map(t => <Tag tag={t} url={baseUrl} key={t.id} /> )}
    </div>
}

const Tag = React.memo(({tag, url}) => {
    return <div className="badge badge-light">
        <a href={url.replace('__tags__', 'tags[]').replace('__id__', tag.id)}
           className="tags-link"
           title={tag.title}
           data-toggle="tooltip"
           data-placement="top"
        ><img alt={tag.title}
              src={"https://img.shields.io/badge/" + tag.title + "-FFFFFF?logo=" + tag.code + "&logoColor=" + tag.color + "&style=for-the-badge"} />
        </a>
    </div>
});

class TagscloudElement extends HTMLElement {

    constructor() {
        super();
        this.observer = null
    }

    connectedCallback () {
        const baseUrl = this.dataset.url

        if(this.observer === null){
            this.observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if(entry.isIntersecting && entry.target === this) {
                        observer.disconnect()
                        render(<Tagscloud baseUrl={baseUrl} />, this)
                    }
                })
            })
        }
        this.observer.observe(this)
    }

    disconnectedCallback () {
        if(this.observer) {
            this.observer.disconnect()
        }
        unmountComponentAtNode(this)
    }
}

customElements.define('tagscloud-component', TagscloudElement);