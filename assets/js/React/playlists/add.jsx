import React, {useState} from 'react';
import {createRoot} from "react-dom/client";
import {Col, Row} from "react-bootstrap";

function Add () {
    const [datas, setDatas] = useState({})
    const [loading, setLoading] = useState(true)
    const [search, setSearch] = useState('')

    const handleSearch = ({currentTarget}) => {
        setSearch(currentTarget.value)
    }

    const handleClick = (event) => {
        event.preventDefault()
        console.log(search)

        setLoading(true)
        searchPlaylist(search)
    }

    const searchPlaylist = async () => {
        try {
            const response = await fetch('/links/search/playlist/' + search, {
                headers: {
                    'Accept': 'application/json'
                },
                method: 'GET'
            }).then(r => r.json())

            setDatas(response['data'])
            setLoading(false)
        } catch (error) {
            console.log(error)
        }
    }

    return (
        <>
            <div className={"mb-2"}>
                <div className={"form-group"}>
                    <label htmlFor="playlistId" className={"form-label"}>ID de la playlist</label>
                    <input type="text"
                           onChange={handleSearch}
                           value={search}
                           id={"playlistId"}
                           className="form-control"
                           placeholder="Rechercher (Nom, Prenom ou INE)..."
                    />
                </div>

                <button className={"btn btn-success"} onClick={handleClick}>Rechercher</button>
            </div>

            {!loading && <>
                <h1 className="h1 mb-3 fw-bold">
                    <i className="bi bi-youtube me-1 text-primary"></i> Ajouter une playlist
                </h1>

                <h2 className="h4 fw-bold text-uppercase text-decoration-underline mb-3">{datas['infos']['title']}</h2>

                <Row>
                    <Col sm={6}>
                        {datas['items'].map(item =>
                        <Row key={item.id}
                            className="minimal-card rounded-4 g-0 overflow-hidden flex-md-row mb-4 position-relative">
                            <Col sm={4}>
                                <div className="link-thumbnail">
                                        <img src={item.snippet.thumbnails.medium.url}
                                             alt=""
                                             className="img-fluid" />
                                </div>
                            </Col>

                            <Col sm={8} className="p-2">
                                <div className="align-self-start">
                                    <div className="video-title fs mb-1">
                                        <strong>{item.snippet.title}</strong>
                                    </div>

                                    <div className="video-meta small my-1">
                                        <i className="bi bi-mortarboard-fill me-1"></i>{item.snippet.channelTitle}
                                    </div>
                                </div>
                            </Col>
                        </Row>
                        )}
                    </Col>
                    <Col sm={6}>
                        <div className={"form-group"}>
                            <label htmlFor={"test"}>Test</label>
                            <input type={"text"} id={"test"} name={"test"} value={""} className={"fom-control"} />
                        </div>
                    </Col>
                </Row>

                {JSON.stringify(datas, null, ' ')}
            </>}
        </>
    );
}

class PlaylistElement extends HTMLElement {
    connectedCallback () {
        const root = createRoot(this)
        root.render(<Add />)
    }
}

customElements.define('playlistadd-component', PlaylistElement);