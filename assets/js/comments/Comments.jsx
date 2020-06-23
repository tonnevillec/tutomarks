import React, {useCallback, useEffect, useRef} from "react";
import {render, unmountComponentAtNode} from 'react-dom';
import {useFetch, usePaginatedFetch} from "./hooks";
import {Field} from "../components/Form";

const dateFormat = {
    dateStyle: 'medium',
    timeStyle: 'short'
}

function Comments ({tuto, user}) {
    const {items: comments, setItems: setComments, load, loading, count, hasMore} = usePaginatedFetch('/api/comments?is_validate=true&tutos=' + tuto)

    const addComment = useCallback(comment => {
        setComments(comments => [comment, ...comments])
    }, [])

    useEffect(() => {
        load()
    }, [])

    return <div>
        <Title count={count}/>
        <hr className="my-4"/>

        {user && <CommentForm tuto={tuto} onComment={addComment} />}

        {/*{{ 'user.want_to_comment'|trans|capitalize }} ? <a href="{{ path('app_register') }}" class="btn btn-outline-info">{{ 'user.register'|trans|capitalize }}</a> <a href="{{ path('app_login') }}" class="btn btn-outline-success">{{ 'user.login'|trans|capitalize }}</a>*/}
        {!user && <Links/>}

        {comments.map(comment => <Comment key={comment.id} comment={comment}/>)}

        {hasMore && <button onClick={load} disabled={loading} className="btn btn-outline-info">Charger la suite</button>}
    </div>
}

const Comment = React.memo(({comment}) => {
    const date = new Date(comment.commented_at)

    return <div className="row mb-4">
        <div className="col">
            <div className="card">
                <div className="card-body">
                    <p className="card-title">Commentaire posté par {comment.user.username ? comment.user.username : comment.user.email} le {date.toLocaleString(undefined, dateFormat)}</p>
                    <hr className="my-2"/>
                    <p className="card-text">{comment.comment}</p>
                </div>
            </div>
        </div>
    </div>
})

const CommentForm = React.memo(({tuto, onComment}) => {
    const ref = useRef(null)
    const onSuccess = useCallback(comment => {
        onComment(comment)
        ref.current.value = ''
    }, [ref, onComment])
    const {load, loading, errors, clearError} = useFetch('/api/comments', 'POST', onSuccess)
    const onSubmit = useCallback(e => {
        e.preventDefault()
        load({
            comment: ref.current.value,
            tutos: "/api/tutos/" + tuto
        })
    }, [load, ref, tuto])

    return <div className="row">
        <div className="col mb-3">
            <form onSubmit={onSubmit}>
                <Field
                    name="comment"
                    help="Les commentaires jugés injurieux ou irrespectueux seront modérés"
                    ref={ref}
                    required
                    minLength="10"
                    onChange={clearError.bind(this, 'comment')}
                    error={errors['comment']}
                >Laissez votre commentaire</Field>

                <div className="form-group">
                    <button className="btn btn-sm btn-outline-success" disabled={loading}>Envoyer</button>
                </div>
            </form>
        </div>
    </div>
})

function Title ({count}) {
    return <h2 className="h2">{count === 0 ? 'Aucun' : count} Commentaire{count > 1 ? 's' : ''}</h2>
}

function Links () {
    return <div className="mb-4">
        Pour laisser un commentaire, veuillez-vous identifer => <a href="/login" className="">Connectez-vous</a>.<br/>
        Si vous n'avez pas encore de compte => <a href="/register" className="">Inscrivez-nous</a>
    </div>
}

class CommentsElement extends  HTMLElement {

    constructor() {
        super()
        this.observer = null
    }

    connectedCallback () {
        const tuto = parseInt(this.dataset.tuto, 10)
        const user = parseInt(this.dataset.user, 10) || null
        if(this.observer === null){
            this.observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if(entry.isIntersecting && entry.target === this) {
                        observer.disconnect()
                        render(<Comments tuto={tuto} user={user} />, this)
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

customElements.define('tuto-comments', CommentsElement)