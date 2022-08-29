import React, {useEffect, useState} from 'react';
import {createRoot} from "react-dom/client";
import {Button, Modal, NavLink} from "react-bootstrap";
import nl2br from "react-nl2br";
import {unmountComponentAtNode} from "react-dom";

function Events () {
    const [events, setEvents] = useState([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        fetchDatas()
    }, [])

    const fetchDatas = async () => {
        try {
            const response = await fetch('/api/events', {
                headers: {
                    'Accept' : 'application/json'
                }
            })
            const datas = await response.json()

            setEvents(datas)
            setLoading(false)
        } catch (error) {
            console.error(error)
        }
    }

    return <>
        {loading && <>Chargement ...</>}

        {!loading && <>
            {events.map(event => <EventCard event={event} key={event.id} />)}
        </>}
    </>
}

const EventCard = React.memo(({event}) => {
    const [show, setShow] = useState(false);

    const handleClose = () => setShow(false);
    const handleShow = () => setShow(true);

    return <>
        <div className="mb-3 rounded-4 overflow-hidden card-event p-2">
            <div className="mb-2">
                <i className="bi bi-calendar3 me-2"></i>{event.startedAtLocal}
            </div>
            <div className="mb-1">
                <a href={ event.url }
                   data-bs-toggle="tooltip"
                   data-bs-placement="top"
                   target="_blank"
                   title="Ouvrir le lien dans un nouvel onglet"
                   className="text-decoration-none">
                    <strong>{event.title}</strong>
                    <i className="ms-1 bi bi-box-arrow-up-right"></i>
                </a>
            </div>

            <div className="video-meta small mb-2">
                <NavLink to="/" className="text-decoration-none">
                    <i className="bi bi-mortarboard-fill me-1"></i>{event.author.title}
                </NavLink>
            </div>

            <span className="d-inline">
                <button type="button"
                        className="btn btn-outline-primary btn-sm me-2"
                        onClick={handleShow}
                >Description</button>

                <Modal show={show} size="lg" onHide={handleClose}>
                    <Modal.Header closeButton>
                        <Modal.Title>
                            <a href={ event.url }
                               data-bs-toggle="tooltip"
                               data-bs-placement="top"
                               target="_blank"
                               title="Ouvrir le lien dans un nouvel onglet"
                               className="text-decoration-none">
                                <strong>{event.title}</strong>
                                <i className="ms-1 bi bi-box-arrow-up-right"></i>
                            </a>
                        </Modal.Title>
                    </Modal.Header>

                    <Modal.Body>{nl2br(event.description)}</Modal.Body>

                    <Modal.Footer>
                        <a href={ event.url }
                           data-bs-toggle="tooltip"
                           data-bs-placement="middle"
                           target="_blank"
                           title="Ouvrir le lien dans un nouvel onglet"
                           className="btn btn-outline-success me-2">
                            Accéder à l'événement<i className="ms-1 bi bi-box-arrow-up-right"></i>
                        </a>
                        <Button variant="primary" onClick={handleClose}>Fermer</Button>
                    </Modal.Footer>
                </Modal>

                {event.is_physical &&
                    <a href="#"
                       className="text-decoration-none"
                       data-bs-toggle="tooltip"
                       data-bs-placement="top"
                       title="Evénement physique"><i className="bi bi-building me-2"></i></a>
                }

                {event.live_on_youtube &&
                    <a href="#"
                       className="text-decoration-none"
                       data-bs-toggle="tooltip"
                       data-bs-placement="top"
                       title="Evénement Youtube"><i className="bi bi-youtube me-2"></i></a>
                }

                {event.live_on_twitch &&
                    <a href="#"
                       className="text-decoration-none"
                       data-bs-toggle="tooltip"
                       data-bs-placement="top"
                       title="Live on Twitch"><i className="bi bi-twitch me-2"></i></a>
                }

                {event.live_on_twitter &&
                    <a href="#"
                       className="text-decoration-none"
                       data-bs-toggle="tooltip"
                       data-bs-placement="top"
                       title="Live Twitter"><i className="bi bi-twitter me-2"></i></a>
                }

                <a href="#"
                   className={"text-decoration-none" + event.is_free ? " text-success" : " text-danger"}
                   data-bs-toggle="tooltip"
                   data-bs-placement="top"
                   title={event.is_free ? "Gratuit" : "Payant"}><i className="bi bi-currency-euro me-2"></i></a>
            </span>
        </div>
    </>
});

class EventsElement extends HTMLElement {
    constructor() {
        super();
        this.observer = null
    }

    connectedCallback () {
        if(this.observer === null){
            this.observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if(entry.isIntersecting && entry.target === this) {
                        observer.disconnect()
                        const root = createRoot(this)
                        root.render(<Events />)
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

customElements.define('events-component', EventsElement);
