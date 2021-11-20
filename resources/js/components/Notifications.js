import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import axios from "axios";
import { Modal } from 'react-bootstrap';

class Notifications extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            notifications: [],
            activeNotification:{},
            showModalOne: false,
            showModalTwo: false,            
            totalUnseenCount: props.count,
            loadMore:true,
            loading:true,
            page:1,
            firstClick: true,
        };
        this.fetchMoreData =  ()  => {
          this.setState({ loading: true });
          if(this.state.loadMore){
            setTimeout(() => {
                var len = this.state.notifications.length;
                const vm = this;
                axios.get(siteUrl + "/notifications?pagination=1&page=" +this.state.page)
                   .then(response => {
                      console.log(response.data.data.length);
                      if (response.data.data.length) {
                         vm.setState({
                            notifications: vm.state.notifications.concat(response.data.data),
                            page: vm.state.page + 1
                         });
                      } else {
                         vm.setState({
                            loadMore: false
                         });
                      }
                      this.setState({ loading: false });
                   })
                   .catch(function(error) {
                      console.log(error);
                   });
             }, 1500);
          }            
      };
    }    
    onOpenModal() {      
      this.setState({ showModalOne: true });
      if(this.state.firstClick)
        this.fetchMoreData();
      this.setState({ firstClick: false });
    }   
    onCloseModal()  {
      this.setState({ showModalOne: false });
    }
    onOpenModalTwo(indexVal, isSeen) {
      this.setState({ activeNotification : this.state.notifications[indexVal] });
      //updating total count in props and sending data to server
      if(!isSeen){
        this.setState({ totalUnseenCount : this.state.totalUnseenCount-1 });
        const formData = new FormData();
        formData.append('notification_id', this.state.notifications[indexVal].id);
        formData.append('_token', document.querySelector("meta[name='csrf-token']").getAttribute("content"));
        axios.post(siteUrl + "/notifications/action",formData)
             .then(response => {
                console.log(response.data);                 
             })
             .catch(function(error) {
                console.log(error);
             });
      }      
      //updating seen value
      const notifications = this.state.notifications;
      notifications[indexVal].seen = 1;
      this.setState({ notifications });
      //updating second modal
      this.setState({ showModalTwo: true });
    }   
    onCloseModalTwo()  {
      this.setState({ showModalTwo: false });
    }
    componentDidMount(prevProps, prevState) {
        //this.fetchMoreData();
     }
    render() {
        return (
            <div>
              <span onClick={(e) => this.onOpenModal() } className="nav-link">
                <i className="far fa-bell"></i>
                <span className="badge badge-warning navbar-badge">{ this.state.totalUnseenCount }</span>
              </span>
              <Modal show={this.state.showModalOne} onHide={(e) => this.onCloseModal()}>
                <Modal.Header closeButton closeLabel="close window"><h3>Bulletins & Announcements</h3></Modal.Header>
                <Modal.Body> 
                  <div className="col-12">                    
                    <div className="row">
                      { !this.state.loadMore && this.state.notifications.length==0?<div className="alert alert-primary" role="alert">No Announcements Yet!.</div>:null }
                      {this.state.notifications.map((notification, index) => (
                        <div className="col-xl-12" key={index}>
                          <div className={ notification.seen?"alert alert-secondary seen":"alert alert-light unseen" } role="alert">
                              <div className="row">
                                <div className="col-xl-10">
                                  { notification.title } - { moment(notification.created_at).format("D/M/Y") }<br />
                                  { notification.description.length>45? notification.description.substring(0, 45)+'...' : notification.description }
                                </div>
                                <div className="col-xl-2 mt-2">
                                  <span onClick={(e) => this.onOpenModalTwo(index, notification.seen) } className="btn btn-primary btn-sm">View</span>
                                </div>
                              </div>
                            
                          </div>
                        </div>
                      ))}
                      {this.state.loading ?<div className="col-12 text-center"><div className="spinner-border text-primary" role="status"><span className="sr-only">Loading...</span></div></div>:null}         
                      {!this.state.loading && this.state.loadMore ?<div className="col-12 text-center"><a onClick={(e) => this.fetchMoreData() }>...More...</a></div>:null}  
                    </div>
                  </div>
                </Modal.Body>
              </Modal>
             <Modal size="lg" show={this.state.showModalTwo} onHide={(e) => this.onCloseModalTwo()} >
                <Modal.Header closeButton closeLabel="close window"><h3>{ this.state.activeNotification.title}</h3>
                </Modal.Header>
                <Modal.Body>
                   <p>Date : { moment(this.state.activeNotification.created_at).format("D/M/Y") }</p>
                   <p>Link : <a href={ this.state.activeNotification.link } target="_blank" className="btn btn-primary btn-sm">Open</a> </p>
                   <p>Description : { this.state.activeNotification.description}</p>
                </Modal.Body>
              </Modal>
            </div>
        );
    }
}
if (document.getElementById('notifications')) {
    const el = document.getElementById('notifications');
    const props = Object.assign({}, el.dataset);
    ReactDOM.render(<Notifications {...props} />, el);
}