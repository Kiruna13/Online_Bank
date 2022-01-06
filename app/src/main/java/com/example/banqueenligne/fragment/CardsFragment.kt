package com.example.banqueenligne.fragment

import android.content.Intent
import android.os.Bundle
import androidx.fragment.app.Fragment
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.CheckBox
import android.widget.Checkable
import android.widget.TextView
import com.android.volley.*
import com.android.volley.toolbox.StringRequest
import com.android.volley.toolbox.Volley
import com.example.banqueenligne.MakeVirement
import com.example.banqueenligne.R
import com.google.android.flexbox.FlexboxLayout
import org.json.JSONObject
import kotlin.properties.Delegates

// TODO: Rename parameter arguments, choose names that match
// the fragment initialization parameters, e.g. ARG_ITEM_NUMBER
private const val ARG_PARAM1 = "param1"
private const val ARG_PARAM2 = "param2"
lateinit var mQueue : RequestQueue
var locked_status by Delegates.notNull<Boolean>()
var opposition_status by Delegates.notNull<Boolean>()
var distance_status by Delegates.notNull<Boolean>()
var foreign_status by Delegates.notNull<Boolean>()

/**
 * A simple [Fragment] subclass.
 * Use the [CardsFragment.newInstance] factory method to
 * create an instance of this fragment.
 */
class CardsFragment : Fragment(), View.OnClickListener {
    // TODO: Rename and change types of parameters
    private var param1: String? = null
    private var param2: String? = null

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
    }


    override fun onCreateView(
        inflater: LayoutInflater, container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? {

        var fetchedUser = arguments?.getString("user")
        var user = JSONObject(fetchedUser)
        var name :String = user["name"].toString()
        var firstname :String = user["first_name"].toString()
        mQueue = Volley.newRequestQueue(this.context)

        getCardInfos(user["user_id"].toString())

        val view = inflater.inflate(R.layout.fragment_cards, container, false)
        view.findViewById<TextView>(R.id.name).text = concat(name, " ", firstname).toUpperCase()
        // Inflate the layout for this fragment

        val b = view.findViewById<CheckBox>(R.id.lockedCheckbox)
        b.setOnClickListener(this)

        return view
    }

    override fun onClick(view: View) {
        when (view.getId()) {
            R.id.lockedCheckbox -> (view.findViewById<CheckBox>(R.id.lockedCheckbox) as Checkable).isChecked = locked_status
            R.id.oppositionCheckbox -> (view.findViewById<CheckBox>(R.id.oppositionCheckbox) as Checkable).isChecked = opposition_status
            R.id.distanceCheckbox -> (view.findViewById<CheckBox>(R.id.distanceCheckbox) as Checkable).isChecked = distance_status
            R.id.foreignCheckbox -> (view.findViewById<CheckBox>(R.id.foreignCheckbox) as Checkable).isChecked = foreign_status
        }
    }

    private fun concat(vararg string: String): String {
        val sb = StringBuilder()
        for (s in string) {
            sb.append(s)
        }

        return sb.toString()
    }

    fun getCardInfos(user_id : String) {
        val url = "http://192.168.0.36/onlineBankAPI/v1/?op=getCard"
        lateinit var data : JSONObject

        val request = object : StringRequest(
            Request.Method.POST, url,
            Response.Listener<String> { response ->
                data = JSONObject(response)
                var card = data.optJSONArray("message")
                var fetchedCard = card!!.getJSONObject(0)
                setCheckboxesValues(fetchedCard)
            },
            Response.ErrorListener { error : VolleyError ->
                println(error)
            }) {
            @Throws(AuthFailureError::class)
            override fun getParams() : Map<String, String> {
                val params = HashMap<String, String>()
                params["user_id"] = user_id
                return params
            }
        }
        mQueue.add(request)
    }

    fun setCheckboxesValues(card : JSONObject) {
        if (card["locked_status"].toString() == "1") {
            locked_status = true
        } else {
            locked_status = false
        }
        if (card["opposition_status"].toString() == "1") {
            opposition_status = true
        } else {
            opposition_status = false
        }
        if (card["distance_status"].toString() == "1") {
            distance_status = true
        } else {
            distance_status = false
        }
        if (card["foreign_status"].toString() == "1") {
            foreign_status = true
        } else {
            foreign_status = false
        }
    }

    companion object {
        /**
         * Use this factory method to create a new instance of
         * this fragment using the provided parameters.
         *
         * @param param1 Parameter 1.
         * @param param2 Parameter 2.
         * @return A new instance of fragment CardsFragment.
         */
        // TODO: Rename and change types and number of parameters
        @JvmStatic
        fun newInstance(param1: String, param2: String) =
            CardsFragment().apply {
                arguments = Bundle().apply {
                    putString(ARG_PARAM1, param1)
                    putString(ARG_PARAM2, param2)
                }
            }
    }
}