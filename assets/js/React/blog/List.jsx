import React, {useEffect, useState} from 'react';
import {createRoot} from "react-dom/client";
// import Tags from "../components/Tags";
import Pagination from "../components/Pagination";
import Tag from "../components/Tag";

function Posts (props) {
    const [datas, setDatas] = useState([]);
    const [loading, setLoading] = useState(true);
    const [currentPage, setCurrentPage] = useState(1);
    const itemsPerPage = 10;
    const {baseUrl} = props;

    useEffect(() => {
        setLoading(true)
        fetchDatas()
    }, [])

    const fetchDatas = async () => {
        try {
            const response = await fetch('/api/posts', {
                headers: {
                    'Accept' : 'application/json'
                }
            })
            const datas = await response.json()

            setDatas(datas)
            setLoading(false)
        } catch (error) {
            console.log(error)
        }
    }

    const handlePageChange = (page) => {
        setCurrentPage(page);
    }

    const paginated = Pagination.getData(datas, currentPage, itemsPerPage);

    return <>
        {loading && <h2>Chargement...</h2>}
        {!loading && <>
            {paginated.length !== 0 && <>
                {paginated.map(post => <article className="post" key={post.id}>
                    <h2>
                        <a href={baseUrl.replace('__slug__', post.slug)}>
                            {post.title}
                        </a>
                    </h2>

                    <p className="post-metadata">
                    <span className="metadata">
                        <i className="fa fa-calendar"></i> {post.publishedAtLocal}
                    </span>
                        <span className="metadata"><i className="fa fa-user"></i> {post.author.fullName}</span>
                    </p>

                    <p>{post.summary}</p>

                    <Tag list={post.tags} />
                </article>
                )}

                {itemsPerPage < datas.length &&
                    <Pagination currentPage={currentPage}
                    itemsPerPage={itemsPerPage}
                    length={datas.length}
                    onPageChanged={handlePageChange}
                    />
                }
            </>}
        </>}
    </>;
}

class PostsElement extends HTMLElement {
    connectedCallback () {
        const baseUrl = this.dataset.url
        const root = createRoot(this)
        root.render(<Posts baseUrl={baseUrl} />)
    }
}

customElements.define('posts-component', PostsElement);