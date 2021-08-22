export default {
  indexById(arr) {
    return {
      data:arr.reduce((map,obj)=>{map[obj.id]=obj;return map},{}),
      order:arr.map(obj=>obj.id),
      isLoaded:true
    }
  },
  ordered(obj){
    return obj.order.map(order=>obj.data[order])
  }

}
