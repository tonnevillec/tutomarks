import React, {useEffect, useState} from 'react';
import {createRoot} from "react-dom/client";
import Pagination from "../components/Pagination";

function HebdooList () {
    const [datas, setDatas] = useState([]);
    const [loading, setLoading] = useState(true);
    const [currentPage, setCurrentPage] = useState(1);
    const [search, setSearch] = useState('');
    const itemsPerPage = 10;
    const [filtered, setFiltered] = useState([]);

    useEffect(() => {
        setLoading(true)
        fetchDatas()
    }, [])

    const fetchDatas = async () => {
        try {
            const response = await fetch('/api/hebdoos', {
                headers: {
                    'Accept' : 'application/json'
                }
            })
            const datas = await response.json()

            setDatas(datas)
            setFiltered(datas)
            setLoading(false)
        } catch (error) {
            console.log(error)
        }
    }

    const handlePageChange = (page) => {
        setCurrentPage(page);
    }

    const handleSearch = ({currentTarget}) => {
        setSearch(currentTarget.value);
        setCurrentPage(1);

        setFiltered(datas.filter(
            d =>
                d.title.toString().toLowerCase().includes(search.toLowerCase()) ||
                d.pseudo.toString().toLowerCase().includes(search.toLowerCase()) ||
                d.url.toString().toLowerCase().includes(search.toLowerCase())
        ))
    }

    const paginated = Pagination.getData(filtered, currentPage, itemsPerPage);

    return <>
        {loading && <>Chargement...</>}
        {!loading && <>
            <div className="row mt-2 mb-3">
                <div className="form-group">
                    <input type="text"
                           onChange={handleSearch}
                           value={search}
                           className="form-control mb-2"
                           placeholder="Rechercher ..."
                    />
                </div>
            </div>

            <div className="row mt-2 mb-3">
                <div className="list-group">
                    {paginated.map(ressource => <div className="list-group-item my-2 rounded-10 card-hover" key={ressource.id}>
                        <div className="mb-1">
                            <a href={ ressource.url }
                               data-bs-toggle="tooltip"
                               data-bs-placement="top"
                               target="_blank"
                               title={"Ouvrir le lien dans un nouvel onglet - " + ressource.title }
                               className="text-decoration-none">
                                <strong>{ ressource.title }</strong><i className="ms-1 bi bi-box-arrow-up-right"></i>
                            </a>
                        </div>

                        <div className="video-meta small mb-2">
                            {ressource.language && <>
                                {ressource.language.logo &&
                                <img src={"/uploads/images/" + ressource.language.logo}
                                     className="img-fluid flag-tag"
                                     alt={ ressource.language }
                                     title={ ressource.language }
                                     data-toggle="tooltip"
                                     data-placement="top"
                                />}
                                {!ressource.language.logo &&
                                <span className="badge bg-primary rounded"
                                      title={ressource.language}
                                      data-toggle="tooltip"
                                      data-placement="top"
                                >{ ressource.language }</span>
                                }
                            </>}

                            {ressource.tags.map(t => <span
                                className={"badge bg-primary rounded ms-2"}
                                key={t.id}
                                title={ t.title }
                                data-toggle="tooltip"
                                data-placement="top"
                            >{t.title}</span>
                            )}
                        </div>

                        <div className="video-meta small mb-2">
                            Ressource partagée par { ressource.pseudo } le { ressource.createdAtLocal }
                        </div>
                    </div>
                    )}
                </div>
            </div>

            <div className="row mt-2 mb-3r">
                {itemsPerPage < filtered.length &&
                    <Pagination currentPage={currentPage}
                                itemsPerPage={itemsPerPage}
                                length={filtered.length}
                                onPageChanged={handlePageChange}
                    />
                }
            </div>
        </>}
    </>
}

class HebdooListElement extends HTMLElement {
    connectedCallback (){
        const root = createRoot(this)
        root.render(<HebdooList />)
    }
};

customElements.define('hebdoolist-component', HebdooListElement);
