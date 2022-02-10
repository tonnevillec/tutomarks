import React, {useEffect} from "react";
import {render, unmountComponentAtNode} from "react-dom";
import {usePaginatedFetch} from "../hooks";
import {Icon} from "../components/Icon";
import {Image} from "../components/Image";
import {Badge} from "../components/Badge";

function Mlts (props) {
    const {items: jobs, load, loading} = usePaginatedFetch('/api/mlt');
    const {logoSrc, logoAlt, logoClass, logoWidth, logoHeight, title} = props

    useEffect(() => {
        load()
    }, [])

    return <div className="video-row pb-2">
        <div className="video-row-title my-4 ml-1">
            <h2 className="h2">
                <Image imgSrc={logoSrc}
                       imgAlt={logoAlt}
                       imgClass={logoClass}
                       imgHeight={logoHeight}
                       imgWidth={logoWidth}
                /> {title}
            </h2>
        </div>

        <div className="row pb-3">
            {jobs.map(j => <Job job={j} key={j.id} />)}
        </div>

        <ShowMore/>
    </div>
}

const Job = React.memo(({job}) => {
    return <div className="col-sm-12 col-md-6 col-lg-4">
        <div className="video mb-3 p-2 bg-orange-100 rounded-10">
            <div className="d-flex mb-2">
                {job.fields['Logo (from Team ID)'][0]['url'].length > 0 &&
                    <div className="me-2">
                        <img src={job.fields['Logo (from Team ID)'][0]['url']} width="100px" height="100px"
                             className="img-thumbnail me-2 rounded-10" alt=""/>
                    </div>
                }

                <div className="align-items-center">
                    <div className="video-title fs my-1">
                        <span className="fw-bold fs-4">{job.fields['Company name (from Team ID)']}</span>
                    </div>
                    {job.fields['Mission (from Team ID)'].map(
                        (value, k) => <Badge key={k}
                                             text={'text-purple-600'}
                                            back={'bg-purple-100'}
                                            icon={'bullseye'}
                                            title={value} />
                    )}

                    {job.fields['Values (from Team ID)'].map(
                        (value, k) => <Badge key={k}
                                             text={'text-red-600'}
                                            back={'bg-red-100'}
                                            icon={'heart'}
                                            title={value} />
                    )}
                </div>
            </div>

            <div className="d-flex justify-content-center mb-2">
                <span className="fs-5">{job.fields['Job title']}</span>
            </div>

            <div className="d-flex justify-content-center mb-2">
                <div className="align-items-center">
                    <Badge text={'text-cyan-600'} back={'bg-cyan-100'} icon={'geo'} title={job.fields['Job location']} />

                    {job.fields['Job type'].map(
                        (value, k) => <Badge key={k}
                                             text={'text-purple-600'}
                                             back={'bg-purple-100'}
                                             icon={'house'}
                                             title={value} />
                    )}

                    {job.fields['Job remote work'].map(
                        (value, k) => <Badge key={k}
                                             text={'text-yellow-600'}
                                             back={'bg-yellow-100'}
                                             icon={'house'}
                                             title={value} />
                    )}

                    <br/>

                    {'Skills/stack' in job.fields &&  job.fields['Skills/stack'].split(",").map(
                        (value, k) => <Badge key={k}
                                             text={'text-green-900'}
                                             back={'bg-green-100'}
                                             icon={''}
                                             title={"#" + value} />
                    )}
                </div>
            </div>

            <div className="d-flex justify-content-center mb-2">
                <div className="align-items-center">
                    <a href={ job.fields['Page URL'] + "?source=tutomarks"}
                       target="_blank"
                       className="btn btn-outline-primary btn-sm"> Retrouvez le job sur My Little Team
                        <Icon margin={"ms-1"} icon={"box-arrow-up-right"} />
                    </a>
                </div>
            </div>
        </div>
    </div>
});

function ShowMore() {
    return <div className="row py-3">
        <a href="https://www.mylittleteam.com/?source=tutomarks" className="col a-show-more text-center small">
            MyLittleTeam <Icon icon="box-arrow-up-right" margin="ms-1"/>
        </a>
    </div>
}

class MltsElement extends HTMLElement {

    constructor() {
        super();
        this.observer = null
    }

    connectedCallback () {
        const logoSrc = this.dataset.logosrc
        const logoAlt = this.dataset.logoalt
        const logoClass = this.dataset.logoclass
        const logoWidth = this.dataset.logowidth
        const logoHeight = this.dataset.logoheight
        const title = this.dataset.title

        if(this.observer === null){
            this.observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if(entry.isIntersecting && entry.target === this) {
                        observer.disconnect()
                        render(<Mlts logoSrc={logoSrc}
                                     logoAlt={logoAlt}
                                     logoClass={logoClass}
                                     logoWidth={logoWidth}
                                     logoHeight={logoHeight}
                                     title={title}
                        />, this)
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

customElements.define('mlts-component', MltsElement);